<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Trang chủ - Zken Mbook</title> 

    <!--Boostrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!--Boostrap Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        .navbar{
            background-color: #c98a84;
            padding: 10px 0;
        }
        
        .navbar-brand{
            font-weight: bold;
            color: #000;
        }

        .navbar-brand span{
            color: green;
        }

        .search-box{
            flex-grow: 1;
            display: flex;
            align-items: center;
            background: white;
            border-radius: 20px;
            padding: 5px 10px;
        }

        .search-box input{
            border: none;
            outline: none;
            flex-grow: 1;
        }
        
        .search-box button{
            background: none;
            border: none;
        }

        .nav-icons{
            display: flex;
            gap: 20px;
            padding: 5px;
        }

        .nav-icons a{
            color: white;
            text-decoration: none;
        }

        .hotline-box {
            display: flex;
            align-items: center; 
            gap: 5px; 
            color: white;
            font-family: Arial, sans-serif;
        }

        .hotline-box i {
            font-size: 20px;
        }

        .hotline-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .hotline-text span {
            font-size: 14px;
            font-weight: bold;
        }

        .hotline-text b {
            font-size: 18px;
        }
    </style>
</head> 
<body> 
    <nav class="navbar navbar-expand-lg"> 
        <div class="container">

            <!--Chèn logo-->
            <a class="navbar-brand" href="#">Zken <span>Mbook</span></a> 
            <!--Menu-->
            <div class="category-menu">
                <a href="#" class="btn btn-dark"><i class="bi bi-list"></i></a>
            </div>

            <!--Menu-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" datatarget="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> 
                <span class="navbar-toggler-icon"></span> 
            </button> 

            <!--Nội dung Navbar-->
            <div class="collapse navbar-collapse" id="navbarNav"> 
                <div class = "mx-auto search-box">
                    <input type="text" placeholder="Hôm nay bạn mua gì?">
                    <button><i class ="bi bi-search"></i></button>
                </div>

                <div class="hotline-box">
                    <i class="bi bi-telephone"></i>
                    <div class="hotline-text">
                        <span class ="text-center">Hotline</span>
                        <b>1800.xxxx</b>       
                    </div>
                   
                    <a href= "app\views\pages\cart.php" class="btn btn-white"><i class="bi bi-cart"></i></a>
                    <a href="#" class="btn btn-dark"><i class="bi bi-person-square"></i></a>
                </div>
            </div> 
        </div> 
    </nav>

     <!-- Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> 
</body> 
</html> 
