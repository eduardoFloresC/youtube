<?php 
	require_once 'clases/respuestas.class.php';



	$_respuestas = new respuestas();

	if ($_SERVER['REQUEST_METHOD']== "GET") {
		//echo "hola get";
		if (isset($_GET['palabra'])&&$_GET['palabra']!='') {
			$busqueda = $_GET['palabra'];
			$busqueda = str_replace(' ', '', $busqueda);
			$api_youtube = 'AIzaSyCKWhfBbts6q38Hcn54a4Qf-x2e2PY09aQ';

			$url_youtube='https://www.googleapis.com/youtube/v3/search';


			$type = 'videos';

			$part = 'id,snippet';

			$url = $url_youtube;
			$url.='?key='.$api_youtube;
			$url.='&part='.$part;
			$url.='&order=relevance';
			$url.='&q='.$busqueda;
			//$url.='&regionCode='.$region_code;
			$url.='&type='.$type;
			$url.='&maxResults=10';
			//echo $url.'<br>';

			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_HEADER,false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);

			$phpObj =json_decode($result,true);

			$respuesta_grande=array();
			foreach ($phpObj['items'] as $key => $value) {
				if (isset($value['id']['videoId'])) {
					$elemento = array(
						"published_at"=>$value['snippet']['publishedAt'],
						"id"=>$value['id']['videoId'],
						"title"=>$value['snippet']['title'],
						"description"=>$value['snippet']['description'],
						"thumbnail"=>$value['snippet']['thumbnails']['default']['url'],
						"extra"=>array("Nombre del canal"=>$value['snippet']['channelTitle'])
					);
					//$elemento['published_at']=$value['snippet']['publishedAt'];
					$respuesta_grande[]=$elemento;
				}else{
					$respuesta_grande[]=array("Error"=>"Video no encontrado");
				}
			}

			//

			header("Content-Type: application/json");

			print_r(json_encode($respuesta_grande,true));
			http_response_code(200);
		}
		else{
			print_r(json_encode($_respuestas->error_400()));
			http_response_code(400);
		}


		
		

	}
	else if ($_SERVER['REQUEST_METHOD']== "POST") {
		# code...
	}else if ($_SERVER['REQUEST_METHOD']== "PUT") {
		# code...
	}else if ($_SERVER['REQUEST_METHOD']== "DELETE") {
		# code...
	}else{
		header('Content-Type: application/json');
		$datosArray = $_respuestas->error_405();
		echo json_encode($datosArray);
	}

 ?>
		}
