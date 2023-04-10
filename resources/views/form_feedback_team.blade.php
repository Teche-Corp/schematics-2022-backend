<!DOCTYPE html>
<html lang="en">

<head>
    <link href="{{ asset("css/app.css") }}" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        function CheckColors(val){
            document.getElementById("jenis_pembayaran").style.fontWeight = "600";
            let element=document.getElementById('jenis_pembayaran_lain');
            if(val==="" ||val=== "lain") {
                element.value = ""
                element.style.display='block';
            }
            else {
                element.value = "-"
                element.style.display='none';
            }
        }
        function updateRangeLabel(val) {
            $('#range-value').text(val)
        }
        $(document).ready(function () {
            (function () {
                'use strict'
                const forms = document.querySelectorAll('.requires-validation')
                Array.from(forms)
                    .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
        })
    </script>
    <title>Feedback Team</title>
    <style>
        .background-image {
            position: absolute !important;
            left: 0;
            top: 0;
            min-height: 100%;
            min-width: 100%;
            overflow-x: hidden;
            width: 100%;
            background-size: cover;
            height: 100%;
            background-image: url({{asset( 'img/background-sch.jpg') }});
        }
    </style>
</head>

<body>
<div class="background-image">
    <div class="form-body">
        <div class="row">
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items" style="background-color: #303334">
                        <h3>Feedback Team</h3>
                        <form class="requires-validation" method="POST" action="{{ url('/feedback') }}" novalidate>
                            @csrf

                            <div class="col-md-12">
                                <input class="form-control" type="text" name="nama_ketua" placeholder="* Nama Ketua" required>
                                <div class="invalid-feedback">Nama Ketua harus diisi!</div>
                            </div>

                            <div class="col-md-12">
                                <input class="form-control" type="text" name="nama_anggota_1" placeholder="Nama Anggota 1">
                            </div>

                            <div class="col-md-12">
                                <input class="form-control" type="text" name="nama_anggota_2" placeholder="Nama Anggota 2">
                            </div>

                            <div class="col-md-12">
                                <input class="form-control" type="text" name="nama_sekolah" placeholder="* Nama Sekolah" required>
                                <div class="invalid-feedback">Nama Sekolah harus diisi!</div>
                            </div>

                            <br>

                            <div class="col-md-12">
                                <label class="mb-3 mr-1">Tingkat Kepuasan Mengikuti Perlombaan Schematics: </label>
                                <label id="range-value">5</label>
                                <input class="form-control" oninput="updateRangeLabel(this.value)" type="range" min="1" max="10" value="5" name="kepuasan" required>
                            </div>

                            <br>

                            <div class="col-md-12">
                                <label class="mb-3 mr-1">Babak Soal: </label><br>
                                <input type="radio" id="babak-soal-1" name="babak_soal" value="1">
                                <label for="babak-soal-1">1</label>&#160;
                                <input type="radio" id="babak-soal-2" name="babak_soal" value="2">
                                <label for="babak-soal-2">2</label>&#160;
                                <input type="radio" id="babak-soal-3" name="babak_soal" value="3">
                                <label for="babak-soal-3">3</label>&#160;
                                <input type="radio" id="babak-soal-4" name="babak_soal" value="4">
                                <label for="babak-soal-4">4</label>&#160;
                                <input type="radio" id="babak-soal-5" name="babak_soal" value="5">
                                <label for="babak-soal-5">5</label>&#160;


                                <div class="invalid-feedback mv-up">Babak Soal harus diisi!</div>
                            </div>

                            <br>

                            <div class="col-md-12">
                                <label class="mb-3 mr-1">Babak Game: </label><br>
                                <input type="radio" id="babak-game-1" name="babak_game" value="1" required>
                                <label for="babak-game-1">1</label>&#160;
                                <input type="radio" id="babak-game-2" name="babak_game" value="2" required>
                                <label for="babak-game-2">2</label>&#160;
                                <input type="radio" id="babak-game-3" name="babak_game" value="3" required>
                                <label for="babak-game-3">3</label>&#160;
                                <input type="radio" id="babak-game-4" name="babak_game" value="4" required>
                                <label for="babak-game-4">4</label>&#160;
                                <input type="radio" id="babak-game-5" name="babak_game" value="5" required>
                                <label for="babak-game-5">5</label>&#160;


                                <div class="invalid-feedback mv-up">Babak Game harus diisi!</div>
                            </div>

                            <br>

                            <div class="col-md-12">
                                <label class="mb-3 mr-1">Apakah Terdapat Kendala dalam Perlombaan?: </label><br>
                                <input type="radio" id="ada-kendala" name="kendala" value="true" required>
                                <label for="ada-kendala">Ada</label>&#160;
                                <input type="radio" id="tidak-ada-kendala" name="kendala" value="false" required>
                                <label for="tidak-ada-kendala">Tidak Ada</label>&#160;

                                <div class="invalid-feedback mv-up">Cek Kendala harus diisi!</div>
                            </div>

                            <br>

                            <div class="col-md-12">
                                <input class="form-control" type="text" name="kesan" placeholder="* Kesan Mengikuti Perlombaan Schematics" required>
                                <div class="invalid-feedback">Kesan harus diisi!</div>
                            </div>

                            <div class="col-md-12">
                                <input class="form-control" type="text" name="kritik_saran" placeholder="* Kritik dan Saran" required>
                                <div class="invalid-feedback">Kritik dan Saran harus diisi!</div>
                            </div>

                            <br>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label">data-data diatas sudah benar</label>
                            </div>



                            <div class="form-button mt-3">
                                <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
