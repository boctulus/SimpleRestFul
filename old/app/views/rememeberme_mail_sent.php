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

        <br/>
        <div>
            Un correo con el enlace de recuperación de contraseña fue enviado a <span id='mail'>correo</span>
            <p/><br/>
            Revíselo cuanto antes, tiene vencimiento.
        </div>    
    </div>
</div>    