<style>
    .tex {
        color: white;
        transition: 0.3s;
        border: 2px solid transparent;
    }

    .tex:hover {
        color:rgb(222, 182, 38);
    }
</style>

<footer class="bg-dark text-light m-0 py-2">
    <div class="container">
        <div class="row align-items-center d-flex justify-content-between">
            <p class="col-lg-3 d-none d-lg-block mb-0" style="font-size: 13px;">
                © Designed & Developed by Faculty of Computing
            </p>

            <div class="border col-lg-3 d-none d-lg-block mb-0"></div>
            <div class="border col-lg-3 d-none d-lg-block mb-0"></div>

            <div class="col-12 col-lg-3 text-center text-lg-end">

            <a href="#" class="btn btn-outline-light me-2" id="backToTopBtn">Back to Top</a>
            <a href="#" class="tex mx-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                <a href="#" class="tex mx-3"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="#" class="tex mx-3"><i class="fab fa-instagram fa-lg"></i></a>

            </div>

            <p class="col-12 d-lg-none text-center mb-0" style="font-size: 15px;">
                © Designed & Developed by Faculty of Computing
            </p>
        </div>
    </div>
</footer>
<script>
    window.addEventListener('load', () => {
        const btn = document.getElementById("backToTopBtn");
        if (document.body.scrollHeight <= window.innerHeight) {
            btn.style.display = "none";
        }
    });
</script>

