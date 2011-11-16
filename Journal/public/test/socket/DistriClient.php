<?php
echo "Program starts at ".date('h:i:s')."\n";

$timeout = 10;
$result = array();
$sockets = array();
$convenient_read_block = 8192;

/* Issue all requests simultaneously; there's no blocking*/
$delay = 15;
$id = 0;
while ($delay >0)
{
	$s = stream_socket_client("phaseit.net:80",$errno,
			$errstr,$timeout,
			STREAM_CLIENT_ASYNC_CONNECT|STREAM_CLIENT_CONNECT
	);
	if ($s)
	{
		$sockets[$id++] = $s;
		$http_message = "GET /demonstration/delay?delay=".$delay.
				" HTTP/1.0 Host: phaseit.net ";
		fwrite($s, $http_message);
	}
	else 
	{
		echo "Stream ".$id." failed to open correctly";
	}
	$delay -= 3;
}
while(count($sockets))
{
	$read = $sockets;
	stream_select($read, $w=NULL, $e=NULL, $timeout);
	if (count($read))
	{
		/*stream_select generally shuffles $read,so we need to compute from which socket(s) we're reading*/
		foreach ($read as $r)
		{
			$id = array_search($r, $sockets);
			$data = fread($r, $convenient_read_block);
			/*A socket is readble either because it has data to read, or because if's at EOF*/
			if (strlen($data)==0)
			{
				echo "Stream ".$id." closes at ".date('h:i:s')."\n";
				fclose($r);
				unset($sockets[$id]);
			}
			else
			{
				$result[$id] .= $data;
			}
		}
	}
	else
	{
		/*A time-out means that *all*streams have failed to receive a response*/
		echo "Time-out!";
		break;
	}
}
