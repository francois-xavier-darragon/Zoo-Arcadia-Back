#!/bin/bash

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

get_all_archive_tags() {
    git tag -l "archive/*" | sed 's/archive\///'
}

get_merged_branches() {
    git for-each-ref --format='%(refname:short)' refs/remotes/origin/ |
    grep -vE "origin/(develop|main|HEAD)" |
    sed 's/origin\///'
}

display_branches() {
    local branches="$1"
    local message="$2"
    
    if [ ! -z "$branches" ]; then
        echo -e "${YELLOW}$message${NC}"
        for branch in $branches; do
            local date=$(git log -1 --format=%cd --date=short develop --grep="Merge pull request .* from .*/$branch" || 
                         echo "Date inconnue")
            echo "$date - $branch"
        done
        echo
    fi
}

archive_branch() {
    local branch_name=$1
    local tag_name="archive/$branch_name"
    
    echo -e "${YELLOW}Traitement de la branche : $branch_name${NC}"
    

    local merge_commit=$(git log --merges -1 --grep="Merge pull request .* from .*/$branch_name" develop --format=%H)
    
    if [ -z "$merge_commit" ]; then
        echo -e "${RED}Impossible de trouver un commit de merge pour la branche '$branch_name'. Ignorée.${NC}"
        return 0
    fi
    
 
    local commit_list=$(git log --oneline $merge_commit^2..$merge_commit^1 --format="%h %s")
    
    local tag_message=$(cat << EOF
Archive de la branche $branch_name

Liste des commits :
$commit_list
EOF
)
    
    if git tag -f -a "$tag_name" -m "$tag_message" $merge_commit; then
        echo -e "${GREEN}Tag '$tag_name' créé ou mis à jour pour la branche '$branch_name'.${NC}"
        return 1
    else
        echo -e "${RED}Erreur lors de la création ou mise à jour du tag pour '$branch_name'.${NC}"
        return 2
    fi
}

main() {
    git fetch --all --prune
    
    if ! git checkout develop; then
        echo -e "${RED}Erreur : Impossible de basculer sur la branche develop.${NC}"
        exit 1
    fi
    git pull origin develop
    
    local current_branches=$(get_merged_branches)
    local archive_tags=$(get_all_archive_tags)
    local all_branches=$(echo -e "${current_branches}\n${archive_tags}" | sort -u)
    
    display_branches "$current_branches" "Branches actuellement présentes sur le dépôt distant :"
    display_branches "$(echo "$all_branches" | grep -vxF -f <(echo "$current_branches"))" "Branches précédemment mergées et supprimées :"
    
    if [ -z "$all_branches" ]; then
        echo -e "${YELLOW}Aucune branche à archiver n'a été trouvée.${NC}"
        exit 0
    fi
    
    read -p "Voulez-vous créer ou mettre à jour des tags d'archive pour ces branches ? (o/n) " confirm_archive
    if [[ $confirm_archive != [oO] ]]; then
        echo -e "${YELLOW}Opération annulée.${NC}"
        exit 0
    fi
    
    local tags_updated=false
    for branch in $all_branches; do
        archive_branch $branch
        if [ $? -eq 1 ]; then
            tags_updated=true
        fi
    done
    
    if $tags_updated; then
        if git push origin --tags -f; then
            echo -e "${GREEN}Les tags d'archive ont été poussés vers le dépôt distant.${NC}"
        else
            echo -e "${RED}Erreur lors de la poussée des tags vers le dépôt distant.${NC}"
        fi
    else
        echo -e "${YELLOW}Aucun nouveau tag créé ou mis à jour. Rien à pousser vers le dépôt distant.${NC}"
    fi
    
    echo -e "${GREEN}Opération terminée. Des tags d'archive ont été créés ou mis à jour pour toutes les branches mergées dans develop.${NC}"
}

main