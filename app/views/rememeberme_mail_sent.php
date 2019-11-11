<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        var mail = window.atob(window.location.pathname.split('/')[3]);
        document.querySelector('#mail').innerText = mail;
    });    
</script>

<style>
    #mail {
        color: blue;
    }
</style>

<div class="row vcenter">
	<div class="col-xs-12 col-sm-12 col-md-6 col-md-push-3">
        <h1>Correo enviado</h1>

        <center>
            <img src="<?= assets('img/mail.png') ?>" style="height: 100px;" />
        </center>
        
        <p/><p/>     
        Si la dirección de correo <span id='mail'>correo</span> es válida, 
        un correo con el enlace de recuperación de contraseña fue enviado a dicha dirección<br/><p/>

        Revíselo cuanto antes, tiene vencimiento.    
    </div>
</div>    