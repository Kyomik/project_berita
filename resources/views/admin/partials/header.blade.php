<!DOCTYPE html>
<!--=== Coding by CodingLab | www.codinglabweb.com === -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!----======== CSS ======== -->
     
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <title>Admin Dashboard Panel</title>
    <style type="text/css">
    .fix-bg-image{
      background-position: center; /* Center the image */
      background-repeat: no-repeat; /* Do not repeat the image */
      background-size: cover;
    }
    /* ===== Google Font Import - Poppins ===== */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    :root{
        /* ===== Colors ===== */
        --primary-color: #0E4BF1;
        --panel-color: #FFF;
        --text-color: #000;
        --black-light-color: #707070;
        --border-color: #e6e5e5;
        --toggle-color: #DDD;
        --box1-color: #4DA3FF;
        --box2-color: #FFE6AC;
        --box3-color: #E7D1FC;
        --title-icon-color: #fff;
        
        /* ====== Transition ====== */
        --tran-05: all 0.5s ease;
        --tran-03: all 0.3s ease;
        --tran-03: all 0.2s ease;
    }

    body{
        min-height: 100vh;
        background-color: var(--primary-color);
    }
    body.dark{
        --primary-color: #3A3B3C;
        --panel-color: #242526;
        --text-color: #CCC;
        --black-light-color: #CCC;
        --border-color: #4D4C4C;
        --toggle-color: #FFF;
        --box1-color: #3A3B3C;
        --box2-color: #3A3B3C;
        --box3-color: #3A3B3C;
        --title-icon-color: #CCC;
    }
    /* === Custom Scroll Bar CSS === */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #0b3cc1;
    }

    body.dark::-webkit-scrollbar-thumb:hover,
    body.dark .activity-data::-webkit-scrollbar-thumb:hover{
        background: #3A3B3C;
    }

   .nav{
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 250px;
        padding: 10px 14px;
        background-color: var(--panel-color);
        border-right: 1px solid var(--border-color);
        transition: var(--tran-05);
    }
   .nav.close{
        width: 73px;
    }
   .nav .logo-name{
        display: flex;
        align-items: center;
    }
   .nav .logo-image{
        display: flex;
        justify-content: center;
        min-width: 45px;
    }
   .nav .logo-image img{
        width: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

   .nav .logo-name .logo_name{
        font-size: 22px;
        font-weight: 600;
        color: var(--text-color);
        margin-left: 14px;
        transition: var(--tran-05);
    }
   .nav.close .logo_name{
        opacity: 0;
        pointer-events: none;
    }
   .nav .menu-items{
        margin-top: 40px;
        height: calc(100% - 90px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .menu-items li{
        list-style: none;
    }
    .menu-items li a{
        display: flex;
        align-items: center;
        height: 50px;
        text-decoration: none;
        position: relative;
    }
    .nav-links li a:hover:before{
        content: "";
        position: absolute;
        left: -7px;
        height: 5px;
        width: 5px;
        border-radius: 50%;
        background-color: var(--primary-color);
    }
    body.dark li a:hover:before{
        background-color: var(--text-color);
    }
    .menu-items li a i{
        font-size: 24px;
        min-width: 45px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--black-light-color);
    }
    .menu-items li a .link-name{
        font-size: 18px;
        font-weight: 400;
        color: var(--black-light-color);    
        transition: var(--tran-05);
    }
   .nav.close li a .link-name{
        opacity: 0;
        pointer-events: none;
    }
    .nav-links li a:hover i,
    .nav-links li a:hover .link-name{
        color: var(--primary-color);
    }
    body.dark .nav-links li a:hover i,
    body.dark .nav-links li a:hover .link-name{
        color: var (--text-color);
    }
    .menu-items .logout-mode{
        padding-top: 10px;
        border-top: 1px solid var(--border-color);
    }
    .menu-items .mode{
        display: flex;
        align-items: center;
        white-space: nowrap;
    }
    .menu-items .mode-toggle{
        position: absolute;
        right: 14px;
        height: 50px;
        min-width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .mode-toggle .switch{
        position: relative;
        display: inline-block;
        height: 22px;
        width: 40px;
        border-radius: 25px;
        background-color: var(--toggle-color);
    }
    .nav .nav-links li {
    position: relative;
}

.nav .nav-links li .sub-nav {
    overflow-y: auto;
    overflow-x: hidden;
    display: none;
    list-style: none;
}

.nav .nav-links li.show-sub-menu .sub-nav {
    display: block;
}

.nav .nav-links li .sub-nav li{
    /*border: 1px solid black*/
}

.nav .nav-links li .sub-nav li a{
    display: flex;
    align-items: center;
    height: 40px;
    text-decoration: none;
    /*padding-left: 45px;  Adjust based on your design */
    color: var(--black-light-color);    
    transition: var(--tran-05);
}

.nav .nav-links li .sub-nav li a:hover {
    color: var(--primary-color);
}

body.dark .nav-links li .sub-nav li a:hover {
    color: var(--text-color);
}

    .switch:before{
        content: "";
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        height: 15px;
        width: 15px;
        background-color: var(--panel-color);
        border-radius: 50%;
        transition: var(--tran-03);
    }
    body.dark .switch:before{
        left: 20px;
    }

    .dashboard{
        position: relative;
        left: 250px;
        background-color: var(--panel-color);
        min-height: 100vh;
        width: calc(100% - 250px);
        padding: 10px 14px;
        transition: var(--tran-05);
    }
    .nav.close ~ .dashboard{
        left: 73px;
        width: calc(100% - 73px);
    }
    .dashboard .top{
        position: fixed;
        top: 0;
        left: 250px;
        display: flex;
        width: calc(100% - 250px);
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        background-color: var(--panel-color);
        transition: var(--tran-05);
        z-index: 10;
    }
    .nav.close ~ .dashboard .top{
        left: 73px;
        width: calc(100% - 73px);
    }
    .dashboard .top .sidebar-toggle{
        font-size: 26px;
        color: var(--text-color);
        cursor: pointer;
    }
    .dashboard .top .search-box{
        position: relative;
        height: 45px;
        max-width: 600px;
        width: 100%;
        margin: 0 30px;
    }
    .top .search-box input{
        position: absolute;
        border: 1px solid var(--border-color);
        background-color: var(--panel-color);
        padding: 0 25px 0 50px;
        border-radius: 5px;
        height: 100%;
        width: 100%;
        color: var(--text-color);
        font-size: 15px;
        font-weight: 400;
        outline: none;
    }
    .top .search-box i{
        position: absolute;
        left: 15px;
        font-size: 22px;
        z-index: 10;
        top: 50%;
        transform: translateY(-50%);
        color: var(--black-light-color);
    }
    .top img{
        width: 40px;
        border-radius: 50%;
    }
    .dashboard .dash-content{
        padding-top: 50px;
    }
    .dash-content .title{
        display: flex;
        align-items: center;
        margin: 60px 0 30px 0;
    }
    .dash-content .title i{
        position: relative;
        height: 35px;
        width: 35px;
        background-color: var(--primary-color);
        border-radius: 6px;
        color: var(--title-icon-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .dash-content .title .text{
        font-size: 24px;
        font-weight: 500;
        color: var(--text-color);
        margin-left: 10px;
    }
    .dash-content .boxes{
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .dash-content .boxes .box{
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 12px;
        width: calc(100% / 3 - 15px);
        padding: 15px 20px;
        background-color: var(--box1-color);
        transition: var(--tran-05);
        margin-top: 15px; /* Menambahkan margin atas */
    }
    .boxes .box i{
        font-size: 35px;
        color: var(--text-color);
    }
    .boxes .box .text{
        white-space: nowrap;
        font-size: 18px;
        font-weight: 500;
        color: var(--text-color);
    }
    .boxes .box .number{
        font-size: 40px;
        font-weight: 500;
        color: var(--text-color);
    }
    .boxes .box.box2{
        background-color: var(--box2-color);
    }
    .boxes .box.box3{
        background-color: var(--box3-color);
    }
    .dash-content .activity .activity-data{
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    .activity .activity-data{
        display: flex;
    }
    .activity-data .data{
        display: flex;
        flex-direction: column;
        margin: 0 15px;
    }
    .activity-data .data-title{
        font-size: 20px;
        font-weight: 500;
        color: var(--text-color);
    }
    .activity-data .data .data-list{
        font-size: 18px;
        font-weight: 400;
        margin-top: 20px;
        white-space: nowrap;
        color: var(--text-color);
    }

    @media (max-width: 1000px) {
        .nav{
            width: 73px;
        }
        .nav.close{
            width: 250px;
        }
        .nav .logo_name{
            opacity: 0;
            pointer-events: none;
        }
        .nav.close .logo_name{
            opacity: 1;
            pointer-events: auto;
        }
        .nav li a .link-name{
            opacity: 0;
            pointer-events: none;
        }
        .nav.close li a .link-name{
            opacity: 1;
            pointer-events: auto;
        }
        .nav ~ .dashboard{
            left: 73px;
            width: calc(100% - 73px);
        }
        .nav.close ~ .dashboard{
            left: 250px;
            width: calc(100% - 250px);
        }
        .nav ~ .dashboard .top{
            left: 73px;
            width: calc(100% - 73px);
        }
        .nav.close ~ .dashboard .top{
            left: 250px;
            width: calc(100% - 250px);
        }
        .activity .activity-data{
            overflow-X: scroll;
        }
    }

    @media (max-width: 780px) {
        .dash-content .boxes .box{
            width: calc(100% / 2 - 15px);
            margin-top: 15px;
        }
    }
    @media (max-width: 560px) {
        .dash-content .boxes .box{
            width: 100% ;
        }
    }
    @media (max-width: 400px) {
        .nav{
            width: 0px;
        }
        .nav.close{
            width: 73px;
        }
        .nav .logo_name{
            opacity: 0;
            pointer-events: none;
        }
        .nav.close .logo_name{
            opacity: 0;
            pointer-events: none;
        }
        .nav li a .link-name{
            opacity: 0;
            pointer-events: none;
        }
        .nav.close li a .link-name{
            opacity: 0;
            pointer-events: none;
        }
        .nav ~ .dashboard{
            left: 0;
            width: 100%;
        }
        .nav.close ~ .dashboard{
            left: 73px;
            width: calc(100% - 73px);
        }
        .nav ~ .dashboard .top{
            left: 0;
            width: 100%;
        }
        .nav.close ~ .dashboard .top{
            left: 0;
            width: 100%;
        }
    }
    .container-admin{
      /*border: 1px solid black;*/
        padding: 40px 10px 0px 10px;
        height: inherit;
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
        overflow-wrap: break-word;
        word-break: break-all;
    }
    .container-admin > nav{
        height: 60px;
        width: 100%;
    }
    .table-wrapper {
    /*overflow-x: auto;*/
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 8px 12px;
    border: 1px solid var(--border-color);
}


.data-kategori {
    padding: 10px 0px 10px 0px;
    border: 1px solid black;
    display: flex;
    flex-wrap: wrap;
  }
  .data-kategori > button {
    margin: 5px;
  }

  .form-check-inline{
        border: 1px solid black;
        padding: 5px 10px 5px 10px;
    }
    .container-admin > form .features{
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
        box-sizing: border-box;
    }
    .container-admin > .header-berita {
        display: flex;
        flex-direction: column;
        padding: 0 20px;
        border-bottom: 1px solid black;
    }
    .header-berita h4, .header-berita i {
        margin: 0;
    }
    .container-berita {
    padding: 10px 5px;
    display: flex;
    justify-content: center;
}

.container-berita > .nav {
    padding: 0px;
}

.isi-berita {
    padding: 0px 5px 0px 5px;
    font-family: "arial";
    word-spacing: 2px;
    font-size: 17.5px;
    line-height: 21px;
    flex: 8;
}

.gambar-berita {
    /* border: 1px solid black; */
    float: left;
    margin: 0px 7px 10px 0px;
    width: 450px;
    height: 300px;
}

.isi-berita > p {
    -webkit-hyphens: auto;
    -moz-hyphens: auto;
    -ms-hyphens: auto;
    hyphens: auto;
}

.paragraf-berita {
    margin: 12px 0;
}

.paragraf-utama {
    overflow: hidden;
}

.paragraf-utama-text {
    overflow: hidden;
}

    .container-komentar{
        width: 80%;
        margin: auto;
    }

    #komentar-list {
        max-height: 400px; /* Atur tinggi maksimal untuk mencegah elemen komentar yang terlalu besar */
        overflow-y: auto; /* Tambahkan scrollbar jika ada terlalu banyak komentar */
    }

    /* creating css loader */

#loader {
    width: 2rem;
    height: 2rem;
    border: 5px solid #f3f3f3;
    border-top: 6px solid #9c41f2;
    border-radius: 100%;
    margin: 0px auto 10px auto;
    visibility: hidden;
    animation: spin 1s infinite linear;
}
#loader.display {
    visibility: visible;
}
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.btn-check {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    /* Style the labels as buttons */
    .btn.btn-secondary {
        display: inline-block;
        padding: 10px 20px;
        cursor: pointer;
    }

    /* Change the appearance when the radio button is checked */
    .btn-check:checked + .btn-secondary {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    .sub-nav {
        display: none;
    }
    .has-sub-menu.show-sub-menu .sub-nav {
        display: block;
    }

@media (max-width: 600px) {
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    .gambar-berita{
        width: 100%;
    }
}

.paragraft-draft{
        display: flex;
        padding: 5px;
        box-sizing: border-box;
        flex-direction: column;
        border: 1px solid black;
    }

    .status-paragraft{
        background-color: red;
        border: 1px solid black;
        align-self: flex-end;
        padding: 3px 6px 2px 6px;
        min-width: 100px; 
    }
    .status-paragraft > p{
        margin-bottom: 0px;
    }
    .hidden{
        display: none
    }
</style> 


</head>
<body>