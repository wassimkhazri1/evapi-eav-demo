// assets/js/client.js
document.querySelectorAll('.client-value-edit').forEach(input => {
    input.addEventListener('change', function() {
        fetch(`/project/${this.dataset.projectId}/value/${this.dataset.valueId}`, {
            method: 'PATCH',
            body: JSON.stringify({value: this.value})
        });
    });
});