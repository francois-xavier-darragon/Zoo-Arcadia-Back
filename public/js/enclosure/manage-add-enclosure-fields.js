function manageEnclosureField(){
    document.addEventListener('DOMContentLoaded', function() {
        let container = document.querySelector('#enclosures-container');
        let addButton = document.querySelector('#add-enclosure');
        let prototype = container.getAttribute('data-prototype');
        let index = container.children.length;
        let removeTemplate = document.querySelector('#remove-enclosure-template');
    
        function createRemoveLink() {
            return removeTemplate.content.cloneNode(true).firstElementChild;
        }
    
        function addEnclosureForm() {
            let newForm = prototype.replace(/__name__/g, index);
            let template = document.querySelector('#remove-enclosure-template');
            let newElement = template.content.cloneNode(true);

            newElement.querySelector('.enclosure-content').innerHTML = newForm;

            container.appendChild(newElement);
            index++;
        }
    
        addButton.addEventListener('click', addEnclosureForm);
    
        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-enclosure')) {
                e.target.closest('.enclosure-item').remove();
            }
        });
    
        let existingEnclosures = container.querySelectorAll('.enclosure-item');
        existingEnclosures.forEach(function(enclosure) {
            if (!enclosure.querySelector('.remove-enclosure')) {
                let removeLink = createRemoveLink();
                enclosure.appendChild(removeLink);
            }
        });
    });
}