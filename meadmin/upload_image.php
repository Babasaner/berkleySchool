<html>
	<head>
		<title>Selection d'images</title>
	</head>
	<body>
		<style media="screen">
			html, body{
				background-color:#004c40;
				color: white;
				margin: 0;
				padding: 0;
			}
			#error{
				color: white;
				text-align: center;
				background-color: red;
				font-size: 2.5em;
			}
			#error a{
        padding:6px 0 6px 0;
        font:Bold 13px Arial;
        width:90px;
        border-radius:2px;
        border:none;
        text-decoration: none;
        text-align: center;
        background-color: #4CAF50;
        font-size: .5em;
      }
			#home{
				box-shadow: 0px 10px 15px black;
				margin-left: 10px;
				margin-top: 8px;
			}
			header{
				background-color: black;
				height: 8%;
			}
			#ls-img{
				padding:6px 0 6px 0;
				font:Bold 13px Arial;
				background:#4CAF50;
				color:#fff;
				width:90px;
				border-radius:2px;
				box-shadow: 0px 10px 15px black;
				border:none;
				text-decoration: none;
				margin-left: 25px;
				position: absolute;
				top: 5%;
			}
			.label-file {
				cursor: pointer;
				color: #00b1ca;
				font-weight: bold;
			}
			.label-file:hover {
				color: #25a5c4;
			}
			#input, #btn {
					display: none;
			}
			#cont-btn{
				position: absolute;
				left: 150px;
			}
			#btn-label:hover{
				cursor:pointer;
			}
			.file_container{
				display: flex;
				align-items: center;
				align-content: center;
				padding-bottom: 3%;
				position: absolute;
				top: 220px;
				left: 32.5%;
				height: 30%;
				background-color: #004d40;
				box-shadow: 0px 12px 20px black;
			}
		</style>
		<?php
		echo '<header>
						<div id="head">
							<a href="FaireChoix.php"><img src="home.png" alt="" width="30px" height="30px" id="home"></a>

						</div>
			</header>
		<div class="file_container">
			<a href="listeImage.php" id="ls-img">Mes Images</a>
			<form method="post" enctype="multipart/form-data" name="formUploadFile">
				<div><label for="input"> Selectionner</label>
				<input type="file" id="input" name="images[]" multiple="multiple" /></div>
                <div id="cont-btn"><label for="btn" id="btn-label">uploader</label>
                <div><label for="input" class="label-file">Description</label>
				<input type="text" name="desc" >
				<input type="submit" id="btn" value="" name="btnSubmit"/></div>
			</form>
		</div>';
			$place='page d\'ajout d\'images';

				if(isset($_POST["btnSubmit"]))
					{
						$errors = array();
						$uploadedFiles = array();
						$extension = array("jpeg","jpg","png","gif");
						$tailleMax = 2097152;
						$UploadFolder = "../images";

						$counter = 0; // pour compter le nombre d'erreurs

						foreach($_FILES["images"]["tmp_name"] as $key=>$tmp_name){
							$temp = $_FILES["images"]["tmp_name"][$key];
							$name = $_FILES["images"]["name"][$key];

							if(empty($temp))
							{
								break;
							}

							$counter++;
							$UploadOk = true;
							//==========================================//
							// 		verification de la taille des images	//
							//==========================================//
							if($_FILES["images"]["size"][$key] > $tailleMax)
							{
								$UploadOk = false;
								array_push($errors, $name." les images que vous voulez uploader est superieur à 1 MB.");
							}
							//==========================================//
							// 	verification de l'extension de l'image	//
							//==========================================//
							$ext = pathinfo($name, PATHINFO_EXTENSION);
							if(in_array($ext, $extension) == false){
								$UploadOk = false;
								array_push($errors, $name." l'extension de votre image n'est pas en charge par ce programme.");
							}
							//==============================================================//
							// 		verification si le image existe pour eviter les copies	//
							//==============================================================//
							if(file_exists($UploadFolder."/".$name) == true){
								$UploadOk = false;
								array_push($errors, $name." l'image existe dejas.");
							}
							//===================================//
							// 		si aucun erreur est visualisé	 //
							//===================================//
							if($UploadOk == true){
                                move_uploaded_file($temp,$UploadFolder."/".$name);
                                $bdd = new PDO('mysql:host=localhost;dbname=project2', 'root', '');
                                $req = $bdd->prepare('insert into photos(lien, description) value(:v1, :v2)');
                                $req->execute(array(
                                    'v1'=> "images/".$name,
                                    'v2'=> $_POST['desc']
                                ));
							}
						}

						if($counter>0){
							if(count($errors)>0)
							{
								echo "<b>Errors:</b>";
								echo "<br/><ul>";
								foreach($errors as $error)
								{
									echo "<li>".$error."</li>";
								}
								echo "</ul><br/>";
							}
							//==================================================================//
							// 		apres l'upload affiher les nom des images qui sont uploadées	//
							//==================================================================//
							if(count($uploadedFiles)>0){
								echo "<b>Images uploadées:</b>";
								echo "<br/><ul>";
								foreach($uploadedFiles as $fileName)
								{
									echo "<li>".$fileName."</li>";
								}
								echo "</ul><br/>";

								echo count($uploadedFiles)."	image(s) uploadée(s) avec succes.";
							}
						}
						else{
							echo "Svp selectionnez une image à uploader.";
						}
					}

				
		?>
	</body>
</html>