  <script src="js/components.js" data-page="<?php echo $currentPage ?? ''; ?>"></script>
  <script src="js/products.js"></script>
  <?php 
    if (isset($extraScripts)) {
        foreach ($extraScripts as $script) {
            echo "<script src=\"js/$script.js\"></script>";
        }
    }
  ?>
  <script src="js/script.js"></script>
</body>
</html>
