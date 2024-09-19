#!/bin/bash

# Colors for better readability
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to get merged branches, sorted by last commit date
get_sorted_merged_branches() {
    git for-each-ref --sort=committerdate refs/remotes/origin/ --format='%(refname:short)' |
    grep -v "origin/develop" | grep -v "origin/main" | grep -v "origin/HEAD" |
    while read branch; do
        if git merge-base --is-ancestor $branch develop; then
            echo "$branch"
        fi
    done | sed 's/origin\///'
}

# Function to display merged branches
display_merged_branches() {
    echo -e "${YELLOW}Branches distantes mergées dans develop (de la plus ancienne à la plus récente) :${NC}"
    for branch in $merged_branches; do
        last_commit_date=$(git log -1 --format=%cd --date=short origin/$branch)
        echo "$last_commit_date - $branch"
    done
    echo
}

# Function to create or update an annotated archive tag for a branch
update_archive_tag() {
    branch_name=$1
    tag_name="archive/$branch_name"
    
    echo -e "${YELLOW}Mise à jour du tag d'archive pour la branche : $branch_name${NC}"
    
    git fetch origin $branch_name
    
    # Get list of commits
    commit_list=$(git log --oneline develop..origin/$branch_name)
    
    # Create the tag message
    tag_message="Archive de la branche $branch_name\n\nListe des commits :\n$commit_list"
    
    # Check if the tag already exists
    if git rev-parse -q --verify "refs/tags/$tag_name" >/dev/null; then
        # Update existing tag
        git tag -f -a $tag_name -m "$tag_message" origin/$branch_name
        echo -e "${GREEN}Tag annoté '$tag_name' mis à jour pour la branche '$branch_name'.${NC}"
    else
        # Create a new tag
        git tag -a $tag_name -m "$tag_message" origin/$branch_name
        echo -e "${GREEN}Nouveau tag annoté '$tag_name' créé pour la branche '$branch_name'.${NC}"
    fi
}

# Main function
main() {
    git fetch --all --prune
    
    if ! git checkout develop; then
        echo -e "${RED}Erreur : Impossible de basculer sur la branche develop.${NC}"
        exit 1
    fi
    git pull origin develop
    
    merged_branches=$(get_sorted_merged_branches)
    
    if [ -z "$merged_branches" ]; then
        echo -e "${YELLOW}Aucune branche mergée dans develop n'a été trouvée.${NC}"
        exit 0
    fi
    
    display_merged_branches
    
    read -p "Voulez-vous créer ou mettre à jour des tags d'archive annotés pour ces branches ? (o/n) " confirm_archive
    if [[ $confirm_archive != [oO] ]]; then
        echo -e "${YELLOW}Opération annulée.${NC}"
        exit 0
    fi
    
    for branch in $merged_branches; do
        if [ ! -z "$branch" ]; then
            update_archive_tag $branch
        fi
    done
    
    if git push origin --tags -f; then
        echo -e "${GREEN}Les tags d'archive ont été poussés vers le dépôt distant.${NC}"
    else
        echo -e "${RED}Erreur lors de la poussée des tags vers le dépôt distant.${NC}"
    fi
    
    echo -e "${GREEN}Opération terminée. Les tags d'archive ont été créés ou mis à jour pour les branches mergées dans develop.${NC}"
    echo -e "${YELLOW}N'oubliez pas que vous devrez supprimer manuellement les branches si nécessaire.${NC}"
    echo -e "${YELLOW}Pour voir le contenu d'un tag annoté, utilisez 'git show archive/nom_de_branche'.${NC}"
}

# Run the script
main