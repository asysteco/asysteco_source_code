        </div>
            <footer>
                <style>            
                .pie {
                    background-color: #222;
                    position: absolute;
                    display: inline-block;
                    bottom: 0;
                    width: 100%;
                    height: 50px;
                    }
                .copyright {
                    text-align: center;
                    color: #9d9d9d;
                }
                </style>

                <div class="pie">
                    <a href="#" title="Asysteco" alt="Asysteco" aria-label="Asysteco">
                        <div class="logo"></div>
                    </a>
                    <p class="copyright"> Copyright ® Asysteco</p>
                </div> 
            </footer>
    </body>
</html>

<script>
$(".pie").click(function() {
	$(".pie").toggleClass("hidden");
});
</script>