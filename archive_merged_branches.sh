#!/bin/bash

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

get_all_merged_branches() {
    git log --merges --first-parent develop --pretty=format:"%h %b" |
    grep -oP "Merge branch '\K[^']*" | tac
}

get_branch_commits() {
    branch_name=$1
    base_branch="develop"

    merge_base=$(git merge-base $base_branch origin/$branch_name 2>/dev/null || 
                 git merge-base $base_branch $branch_name 2>/dev/null || 
                 git rev-list --max-parents=0 HEAD)

    git log --reverse --pretty=format:"%h %s" $merge_base..origin/$branch_name 2>/dev/null ||
    git log --reverse --pretty=format:"%h %s" $merge_base..$branch_name
}

create_archive_tag() {
    branch_name=$1
    tag_name="archive/$branch_name"
    
    commit_list=$(get_branch_commits $branch_name)
    
    if [ -z "$commit_list" ]; then
        last_commit=$(git rev-list -n 1 develop --before="$(git show -s --format=%cd --date=short develop^{/Merge branch \'$branch_name\'})")
        commit_list=$(git log -1 --pretty=format:"%h %s" $last_commit)
    fi
    
    tag_message="Archive de la branche $branch_name

Liste des commits :
$commit_list"
    
    if git rev-parse -q --verify "refs/tags/$tag_name" >/dev/null; then
        existing_message=$(git tag -l --format='%(contents)' $tag_name)
        if [ "$existing_message" != "$tag_message" ]; then
            git tag -f -a $tag_name -m "$tag_message" $(git rev-list -n 1 develop --before="$(git show -s --format=%cd --date=short develop^{/Merge branch \'$branch_name\'})")
            echo -e "${GREEN}Tag annoté '$tag_name' mis à jour pour la branche '$branch_name'.${NC}"
            return 1
        fi
    else
        git tag -a $tag_name -m "$tag_message" $(git rev-list -n 1 develop --before="$(git show -s --format=%cd --date=short develop^{/Merge branch \'$branch_name\'})")
        echo -e "${GREEN}Nouveau tag annoté '$tag_name' créé pour la branche '$branch_name'.${NC}"
        return 1
    fi
    return 0
}

main() {
    git fetch --all
    
    if ! git checkout develop; then
        echo -e "${RED}Erreur : Impossible de basculer sur la branche develop.${NC}"
        exit 1
    fi
    git pull origin develop
    
    merged_branches=$(get_all_merged_branches)
    
    if [ -z "$merged_branches" ]; then
        echo -e "${YELLOW}Aucune branche mergée dans develop n'a été trouvée.${NC}"
        exit 0
    fi
    
    echo "Branches mergées qui seront archivées (dans l'ordre de merge) :"
    echo "$merged_branches"
    echo

    read -p "Voulez-vous créer ou mettre à jour les tags d'archive pour ces branches ? (o/n) " confirm_archive
    if [[ $confirm_archive != [oO] ]]; then
        echo -e "${YELLOW}Opération annulée.${NC}"
        exit 0
    fi
    
    tags_updated=false
    for branch in $merged_branches; do
        if [ ! -z "$branch" ]; then
            create_archive_tag $branch
            if [ $? -eq 1 ]; then
                tags_updated=true
            fi
        fi
    done
    
    if $tags_updated; then
        if git push origin --tags; then
            echo -e "${GREEN}Les tags d'archive ont été poussés vers le dépôt distant.${NC}"
        else
            echo -e "${RED}Erreur lors de la poussée des tags vers le dépôt distant.${NC}"
        fi
    else
        echo -e "${YELLOW}Aucun tag n'a été modifié. Rien à pousser vers le dépôt distant.${NC}"
    fi
    
    echo -e "${GREEN}Opération terminée. Les tags d'archive ont été créés ou mis à jour pour les branches mergées.${NC}"
}

# Exécuter le script
main