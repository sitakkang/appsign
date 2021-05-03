
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signature</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;1,100;1,300;1,400&display=swap" rel="stylesheet"> 
    <style>
        body{
            /* width : 100%; */
            font-family: 'Poppins', sans-serif;
            background-color: #efefef;
        }

        img.center {
            display: block;
            margin: 0 auto;
        }
        .container{
            padding: 40px 70px ;
        }
        .tema{
            background-color: #ffffff;  
            border-top: 5px solid #28a745; 
            border-radius: 0px 0px 10px 10px; 
            padding: 35px;
        }
       .underline {
          
            border-bottom: 2px solid currentColor;
        }

        .underline--blue {
            border-bottom-color: #2ECC71;
        }

    </style>
</head>

<body>
    <div class="container" style="background-color: #efefef;">
    
        <div class="row d-flex justify-content-center tema">
            <div class="mt-5 mr-5 ml-5">
                        <img width="300" class="center" src="https://apps.imip.co.id/imageapp/logo-imip-full.png" alt="">
                        <!-- <h2><span class="underline underline--blue">DIGITAL SIGN</span></h2> -->
                        <h3 class="populer text-center mt-5 color" style="text-transform: capitalize;">Dear <?php echo $name_signer;?>,</h3> 
                        <p>Digital Signature anda gagal karena token telah expired. Untuk melakukan request token baru, silahkan tekan tombol Request di bawah ini.</p> 
                        <br>
                        
                        <center>
                            <a style="color:#fff;background-color:#2ECC71;border-top:10px solid #2ECC71;border-right:18px solid #2ECC71;border-bottom:10px solid #2ECC71;border-left:18px solid #2ECC71;display:inline-block;text-decoration:none;border-radius:3px;box-sizing:border-box" 
                        href="<?=site_url('approve/request_token/'.$id_surat)?>" role="button">Request</a>
                            <button style="color:#fff;background-color:#2ECC71;border-top:10px solid #2ECC71;border-right:18px solid #2ECC71;border-bottom:10px solid #2ECC71;border-left:18px solid #2ECC71;display:inline-block;text-decoration:none;border-radius:3px;box-sizing:border-box" onclick="self.close()">Close</button>
                        </center>
                       
                        <p>Terima kasih.</p>
                        
                        <div style="">
                         <p>Tanda Tangal Digital PT. Indonesia Morowali Industrial Park</p>
                        
                        </div>
                       
                    </td>
                  
            </div>
        </div>
    </div>
</body>

</html>