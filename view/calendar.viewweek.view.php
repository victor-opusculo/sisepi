<script src="<?php echo URL\URLGenerator::generateFileURL("includes/html2canvas.min.js"); ?>"></script>
<script>
    window.addEventListener("load", function(e)
    {
        var linkBtn = document.getElementById("aDownloadImage");
        html2canvas(document.getElementById('weekCalendarFrame'), { scale: window.devicePixelRatio * 2 }).then(function(canvas) 
        {
            linkBtn.href = canvas.toDataURL();
        });
    });
</script>
<div id="weekCalendarFrame">
    <?php $wcalComp->render(); ?>
</div>
<div class="centControl">
    <a class="linkButton" id="aDownloadImage" href="" download="eventos-da-semana.png">Baixar imagem</a>
</div>