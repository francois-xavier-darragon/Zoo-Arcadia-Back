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

# Function to create an archive tag for a branch
archive_branch() {
    branch_name=$1
    tag_name="archive/$branch_name"
    
    echo -e "${YELLOW}Vérification du tag d'archive pour la branche : $branch_name${NC}"
    
    # Check if the tag already exists
    if git rev-parse -q --verify "refs/tags/$tag_name" >/dev/null; then
        echo -e "${YELLOW}Le tag '$tag_name' existe déjà. Aucune action nécessaire.${NC}"
        return 0
    fi
    
    echo -e "${YELLOW}Création d'un tag d'archive pour la branche : $branch_name${NC}"
    
    git fetch origin $branch_name
    if git tag $tag_name origin/$branch_name; then
        echo -e "${GREEN}Tag '$tag_name' créé pour la branche '$branch_name'.${NC}"
    else
        echo -e "${RED}Erreur lors de la création du tag pour '$branch_name'.${NC}"
        return 1
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
    
    read -p "Voulez-vous créer des tags d'archive pour ces branches ? (o/n) " confirm_archive
    if [[ $confirm_archive != [oO] ]]; then
        echo -e "${YELLOW}Opération annulée.${NC}"
        exit 0
    fi
    
    new_tags_created=false
    for branch in $merged_branches; do
        if [ ! -z "$branch" ]; then
            archive_branch $branch
            if [ $? -eq 0 ]; then
                new_tags_created=true
            fi
        fi
    done
    
    if $new_tags_created; then
        if git push origin --tags; then
            echo -e "${GREEN}Les nouveaux tags d'archive ont été poussés vers le dépôt distant.${NC}"
        else
            echo -e "${RED}Erreur lors de la poussée des tags vers le dépôt distant.${NC}"
        fi
    else
        echo -e "${YELLOW}Aucun nouveau tag n'a été créé. Rien à pousser vers le dépôt distant.${NC}"
    fi
    
    echo -e "${GREEN}Opération terminée. Des tags d'archive ont été créés pour les branches mergées dans develop qui n'en avaient pas encore.${NC}"
    echo -e "${YELLOW}N'oubliez pas que vous devrez supprimer manuellement les branches si nécessaire.${NC}"
}

# Run the script
main