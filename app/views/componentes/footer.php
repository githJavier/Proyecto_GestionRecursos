<?php
// footer.php
?>
</div> <!-- Cierra el div de min-h-screen flex -->

<script>
  function toggleSubmenu(id) {
    const submenu = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    submenu.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
  }
</script>
</body>
</html>