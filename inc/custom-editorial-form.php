<!-- Formulário simplificado para demonstração -->
<div class="wrap">
    <h1>Add New Editorial</h1>
    <form id="custom-editorial-form">
        <input type="text" name="title" placeholder="Enter title" required />
        <input type="number" name="order" placeholder="Order select" required />
        <!-- Adicionar inputs para featured image e industries como necessário -->
        <button type="submit">Create and Edit</button>
    </form>
</div>

<script>
document.getElementById('custom-editorial-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    // AJAX request para enviar os dados
    fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=create_editorial_post', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
    .then(response => {
        if (response.startsWith('http')) {
            window.location.href = response; // Redirecionar para Elementor
        } else {
            alert('Failed to create post: ' + response);
        }
    });
});
</script>
