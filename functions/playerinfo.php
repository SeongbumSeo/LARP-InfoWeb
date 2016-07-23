<?php
session_start();

require('mysqli.php');

if(!isset($_SESSION['id'])) {
	print("0");
	exit;
}

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];

$presult = $db_samp->query("
	SELECT
		a.*,
		IF (b.Name != \"\",
			b.Name,
			\"무직\"
		) FactionName
	FROM
		user_data a
	LEFT JOIN
		faction_data b
	ON
		a.Faction = b.ID
	WHERE
		a.ID = $id
		AND a.Password = '$password'
	ORDER BY a.CreatedTime ASC
	LIMIT 1");
$cresult = $db_samp->query("
	SELECT
		a.*
	FROM
		car_data a
	JOIN
		user_data b
		ON a.OwnerID = b.ID
	WHERE
		a.OwnerType = 1
		AND b.ID = $id
	ORDER BY CreatedTime ASC");
if($data = $presult->fetch_array()) {
	$pnumber = $data['PhoneNumber'] == 0 ? "없음" : $data['PhoneNumber'];
	$origins = Array("미국", "한국", "이탈리아", "일본", "스페인", "러시아", "프랑스", "중국", "이라크", "독일", "영국");
	$origin = $data['Origin'] < 0 || $data['Origin'] > count($origins) ? "알수없음" : $origins[$data['Origin']];
	$money = '$'.number_format($data['Money']);
	$bank = '$'.number_format($data['Bank']);
	$bankbook = sprintf("%03d-%03d", $data['Bankbook']/1000, $data['Bankbook']%1000);

	$returns = "1|";

	$returns .= str_replace('_', ' ', $data['Username'])."|";
	$returns .= $data['Skin']."|";
	$returns .= $data['Health']."|";
	$returns .= $data['Hunger']."|";

	$returns .= "<div>\n";
	$returns .= addData("나이", $data['Age']);
	$returns .= addData("전화번호", $pnumber);
	$returns .= addData("국적", $origin);
	$returns .= "</div>\n";

	$returns .= "<div>\n";
	$returns .= addData("소지금", $money);
	$returns .= addData("은행", $bank);
	$returns .= addData("계좌번호", $bankbook);
	$returns .= "</div>\n";

	$returns .= "<div>\n";
	$returns .= addData("팩션", $data['FactionName']);
	$returns .= addData("직업", getJobName($data['Job']));
	$returns .= "</div>\n";

	$returns .= "<div>\n";
	$returns .= addData("경고", $data['Warns']."/7");
	$returns .= addData("칭찬", $data['Praises']."/3");
	$returns .= "</div>\n";

	$returns .="<div>\n";
	$returns .= addData("위치", getLocationName($data['LastPos']), "white-space: normal;");
	$returns .= "</div>\n";

	unset($data);

	$returns .= "|".$cresult->num_rows;
	while($data = $cresult->fetch_array()) {
		$engine = $data['Engine'] ? "켜져있음" : "꺼져있음";
		$active = $data['Active'] ? "꺼내져있음" : "넣어져있음";
		$locked = $data['Locked'] ? "잠겨있음" : "열려있음";

		$returns .= "|";

		$returns .= getVehicleModelName($data['Model'])."|";
		$returns .= $data['Model']."|";
		$returns .= ($data['Health']/10)."|";
		$returns .= ($data['Fuel']/100000)."|";

		$returns .= addData("번호판", $data['NumberPlate']);
		$returns .= addData("시동", $engine);
		$returns .= addData("상태", $active);
		$returns .= addData("잠금여부", $locked);
		$returns .= addData("블로우", $data['BlowedCnt']."회");
		$returns .= addData("위치", getLocationName($data['LastPos']), "white-space: normal;");

		unset($data);
	}

	print($returns);
}
else
	print("2");

function addData($key, $value, $style=null) {
	if($style != null)
		return "<span style=\"$style\"><b>$key</b>$value</span></b>";
	return "<span><b>$key</b>$value</span>\n";
}

function getJobName($jobid) {
	switch($jobid) {
		case 1:
			$job = "청소부";
			break;
		case 2:
			$job = "차량정비사";
			break;
		case 3:
			$job = "총기상";
			break;
		case 4:
			$job = "마약상";
			break;
		case 5:
			$job = "농부";
			break;
		case 6:
			$job = "노점상";
			break;
		case 7:
			$job = "낚시꾼";
			break;
		case 8:
			$job = "피자배달부";
			break;
		default:
			$job = "무직";
			break;
	}
	return $job;
}

function getVehicleModelName($modelid) {
	$vehiclelist = array(
		"Landstalker",
		"Bravura",
		"Buffalo",
		"Linerunner",
		"Perenniel",
		"Sentinel",
		"Dumper",
		"Firetruck",
		"Trashmaster",
		"Unique Vehicles",
		"Manana",
		"Infernus",
		"Lowriders",
		"Pony",
		"Mule",
		"Cheetah",
		"Ambulance",
		"Leviathan",
		"Moonbeam",
		"Esperanto",
		"Taxi",
		"Washington",
		"Bobcat",
		"Mr Whoopee",
		"BF Injection",
		"Hunter",
		"Premier",
		"Enforcer",
		"Securicar",
		"Banshee",
		"Predator",
		"Bus",
		"Rhino",
		"Barracks",
		"Hotknife",
		"Article Trailer",
		"Previon",
		"Coach",
		"Cabbie",
		"Stallion",
		"Rumpo",
		"RC Bandit",
		"Romero",
		"Packer",
		"Monster",
		"Admiral",
		"Squallo",
		"Seasparrow",
		"Pizzaboy",
		"Tram",
		"Article Trailer 2",
		"Turismo",
		"Speeder",
		"Reefer",
		"Tropic",
		"Flatbed",
		"Yankee",
		"Caddy",
		"Solair",
		"RC Van",
		"Skimmer",
		"PCJ-600",
		"Faggio",
		"Freeway",
		"RC Baron",
		"RC Raider",
		"Glendale",
		"Oceanic",
		"Sanchez",
		"Sparrow",
		"Patriot",
		"Quad",
		"Coastguard",
		"Dinghy",
		"Hermes",
		"Sabre",
		"Rustler",
		"ZR-350",
		"Walton",
		"Regina",
		"Comet",
		"BMX",
		"Burrito",
		"Camper",
		"Marquis",
		"Baggage",
		"Dozer",
		"Maverick",
		"SAN News Maverick",
		"Rancher",
		"FBI Rancher",
		"Virgo",
		"Greenwood",
		"Jetmax",
		"Hotring Racer",
		"Sandking",
		"Blista Compact",
		"Police Maverick",
		"Boxville",
		"Benson",
		"Mesa",
		"RC Goblin",
		"Hotring Racer",
		"Hotring Racer",
		"Bloodring Banger",
		"Rancher",
		"Super GT",
		"Elegant",
		"Journey",
		"Bike",
		"Mountain Bike",
		"Beagle",
		"Cropduster",
		"Stuntplane",
		"Tanker",
		"Roadtrain",
		"Nebula",
		"Majestic",
		"Buccaneer",
		"Shamal",
		"Hydra",
		"FCR-900",
		"NRG-500",
		"HPV1000",
		"Cement Truck",
		"Towtruck",
		"Fortune",
		"Cadrona",
		"FBI Truck",
		"Willard",
		"Forklift",
		"Tractor",
		"Combine Harvester",
		"Feltzer",
		"Remington",
		"Slamvan",
		"Blade",
		"Train Freight",
		"Train Brownstreak",
		"Vortex",
		"Vincent",
		"Bullet",
		"Clover",
		"Sadler",
		"Firetruck LA",
		"Hustler",
		"Intruder",
		"Primo",
		"Cargobob",
		"Tampa",
		"Sunrise",
		"Merit",
		"Utility Van",
		"Nevada",
		"Yosemite",
		"Windsor",
		"Monster A",
		"Monster B",
		"Uranus",
		"Jester",
		"Sultan",
		"tratum",
		"Elegy",
		"Raindance",
		"RC Tiger",
		"Flash",
		"Tahoma",
		"Savanna",
		"Bandito",
		"Train Freight Flat Trailer",
		"Train Streak Trailer",
		"Kart",
		"Mower",
		"Dune",
		"Sweeper",
		"Broadway",
		"Tornado",
		"AT400",
		"DFT-30",
		"Huntley",
		"Stafford",
		"BF-400",
		"Newsvan",
		"Tug",
		"Petrol Trailer",
		"Emperor",
		"Wayfarer",
		"Euros",
		"Hotdog",
		"Club",
		"Train Freight Box Trailer",
		"Article Trailer 3",
		"Andromada",
		"Dodo",
		"RC Cam",
		"Launch",
		"LSPD Police Car",
		"SFPD Police Car",
		"LVPD Police Car",
		"Police Ranger",
		"Picador",
		"S.W.A.T.",
		"Alpha",
		"Phoenix",
		"Glendale Shit",
		"Sadler Shit",
		"Baggage Trailer A",
		"Baggage Trailer B",
		"Tug Stairs Trailer",
		"Boxville",
		"Farm Trailer",
		"Utility Trailer"
	);
	return $vehiclelist[intval($modelid)-400];
}

function GetLocationName($position) {
	$zones = array(
		array("The Big Ear, Bone County", 				array(-410.00, 1403.30, -3.00, -137.90, 1681.20, 200.00)), 
		array("Aldea Malvada, Bone County", 			array(-1372.10, 2498.50, 0.00, -1277.50, 2615.30, 200.00)), 
		array("Angel Pine, Whetstone", 					array(-2324.90, -2584.20, -6.10, -1964.20, -2212.10, 200.00)), 
		array("Arco del Oeste, Bone County", 			array(-901.10, 2221.80, 0.00, -592.00, 2571.90, 200.00)), 
		array("Avispa Country Club, San Fierro", 		array(-2646.40, -355.40, 0.00, -2270.00, -222.50, 200.00)), 
		array("Avispa Country Club, San Fierro", 		array(-2831.80, -430.20, -6.10, -2646.40, -222.50, 200.00)), 
		array("Avispa Country Club, San Fierro", 		array(-2361.50, -417.10, 0.00, -2270.00, -355.40, 200.00)), 
		array("Avispa Country Club, San Fierro", 		array(-2667.80, -302.10, -28.80, -2646.40, -262.30, 71.10)), 
		array("Avispa Country Club, San Fierro", 		array(-2470.00, -355.40, 0.00, -2270.00, -318.40, 46.10)), 
		array("Avispa Country Club, San Fierro", 		array(-2550.00, -355.40, 0.00, -2470.00, -318.40, 39.70)), 
		array("Back o Beyond, Whetstone", 				array(-1166.90, -2641.10, 0.00, -321.70, -1856.00, 200.00)), 
		array("Battery Point, San Fierro", 				array(-2741.00, 1268.40, -4.50, -2533.00, 1490.40, 200.00)), 
		array("Bayside, Tierra Robada", 				array(-2741.00, 2175.10, 0.00, -2353.10, 2722.70, 200.00)), 
		array("Bayside Marina, Tierra Robada", 			array(-2353.10, 2275.70, 0.00, -2153.10, 2475.70, 200.00)), 
		array("Beacon Hill, Flint County", 				array(-399.60, -1075.50, -1.40, -319.00, -977.50, 198.50)), 
		array("Blackfield, Las Venturas", 				array(964.30, 1203.20, -89.00, 1197.30, 1403.20, 110.90)), 
		array("Blackfield, Las Venturas", 				array(964.30, 1403.20, -89.00, 1197.30, 1726.20, 110.90)), 
		array("Blackfield Chapel, Las Venturas", 		array(1375.60, 596.30, -89.00, 1558.00, 823.20, 110.90)), 
		array("Blackfield Chapel, Las Venturas", 		array(1325.60, 596.30, -89.00, 1375.60, 795.00, 110.90)), 
		array("Blackfield Section, Las Venturas", 		array(1197.30, 1044.60, -89.00, 1277.00, 1163.30, 110.90)), 
		array("Blackfield Section, Las Venturas", 		array(1166.50, 795.00, -89.00, 1375.60, 1044.60, 110.90)), 
		array("Blackfield Section, Las Venturas", 		array(1277.00, 1044.60, -89.00, 1315.30, 1087.60, 110.90)), 
		array("Blackfield Section, Las Venturas", 		array(1375.60, 823.20, -89.00, 1457.30, 919.40, 110.90)), 
		array("Blueberry, Red County", 					array(104.50, -220.10, 2.30, 349.60, 152.20, 200.00)), 
		array("Blueberry, Red County", 					array(19.60, -404.10, 3.80, 349.60, -220.10, 200.00)), 
		array("Blueberry Acres, Red County", 			array(-319.60, -220.10, 0.00, 104.50, 293.30, 200.00)), 
		array("Caligula, s Palace, Las Venturas", 		array(2087.30, 1543.20, -89.00, 2437.30, 1703.20, 110.90)), 
		array("Caligula, s Palace, Las Venturas", 		array(2137.40, 1703.20, -89.00, 2437.30, 1783.20, 110.90)), 
		array("Calton Heights, San Fierro", 			array(-2274.10, 744.10, -6.10, -1982.30, 1358.90, 200.00)), 
		array("Chinatown, San Fierro", 					array(-2274.10, 578.30, -7.60, -2078.60, 744.10, 200.00)), 
		array("City Hall, San Fierro", 					array(-2867.80, 277.40, -9.10, -2593.40, 458.40, 200.00)), 
		array("Come-A-Lot, Las Venturas", 				array(2087.30, 943.20, -89.00, 2623.10, 1203.20, 110.90)), 
		array("Commerce, Los Angeles", 					array(1323.90, -1842.20, -89.00, 1701.90, -1722.20, 110.90)), 
		array("Commerce, Los Angeles", 					array(1323.90, -1722.20, -89.00, 1440.90, -1577.50, 110.90)), 
		array("Commerce, Los Angeles", 					array(1370.80, -1577.50, -89.00, 1463.90, -1384.90, 110.90)), 
		array("Commerce, Los Angeles", 					array(1463.90, -1577.50, -89.00, 1667.90, -1430.80, 110.90)), 
		array("Commerce, Los Angeles", 					array(1583.50, -1722.20, -89.00, 1758.90, -1577.50, 110.90)), 
		array("Commerce, Los Angeles", 					array(1667.90, -1577.50, -89.00, 1812.60, -1430.80, 110.90)), 
		array("Conference Center, Los Angeles", 		array(1046.10, -1804.20, -89.00, 1323.90, -1722.20, 110.90)), 
		array("Conference Center, Los Angeles", 		array(1073.20, -1842.20, -89.00, 1323.90, -1804.20, 110.90)), 
		array("Cranberry Station, San Fierro", 			array(-2007.80, 56.30, 0.00, -1922.00, 224.70, 100.00)), 
		array("Creek, Las Venturas", 					array(2749.90, 1937.20, -89.00, 2921.60, 2669.70, 110.90)), 
		array("Dillimore, Red County", 					array(580.70, -674.80, -9.50, 861.00, -404.70, 200.00)), 
		array("Doherty, San Fierro", 					array(-2270.00, -324.10, -0.00, -1794.90, -222.50, 200.00)), 
		array("Doherty, San Fierro", 					array(-2173.00, -222.50, -0.00, -1794.90, 265.20, 200.00)), 
		array("Downtown, San Fierro", 					array(-1982.30, 744.10, -6.10, -1871.70, 1274.20, 200.00)), 
		array("Downtown, San Fierro", 					array(-1871.70, 1176.40, -4.50, -1620.30, 1274.20, 200.00)), 
		array("Downtown, San Fierro", 					array(-1700.00, 744.20, -6.10, -1580.00, 1176.50, 200.00)), 
		array("Downtown, San Fierro", 					array(-1580.00, 744.20, -6.10, -1499.80, 1025.90, 200.00)), 
		array("Downtown, San Fierro", 					array(-2078.60, 578.30, -7.60, -1499.80, 744.20, 200.00)), 
		array("Downtown, San Fierro", 					array(-1993.20, 265.20, -9.10, -1794.90, 578.30, 200.00)), 
		array("Downtown, Los Angeles", 					array(1463.90, -1430.80, -89.00, 1724.70, -1290.80, 110.90)), 
		array("Downtown, Los Angeles", 					array(1724.70, -1430.80, -89.00, 1812.60, -1250.90, 110.90)), 
		array("Downtown, Los Angeles", 					array(1463.90, -1290.80, -89.00, 1724.70, -1150.80, 110.90)), 
		array("Downtown, Los Angeles", 					array(1370.80, -1384.90, -89.00, 1463.90, -1170.80, 110.90)), 
		array("Downtown, Los Angeles", 					array(1724.70, -1250.90, -89.00, 1812.60, -1150.80, 110.90)), 
		array("Downtown, Los Angeles", 					array(1370.80, -1170.80, -89.00, 1463.90, -1130.80, 110.90)), 
		array("Downtown, Los Angeles", 					array(1378.30, -1130.80, -89.00, 1463.90, -1026.30, 110.90)), 
		array("Downtown, Los Angeles", 					array(1391.00, -1026.30, -89.00, 1463.90, -926.90, 110.90)), 
		array("Downtown, Los Angeles", 					array(1507.50, -1385.20, 110.90, 1582.50, -1325.30, 335.90)), 
		array("East Beach, Los Angeles", 				array(2632.80, -1852.80, -89.00, 2959.30, -1668.10, 110.90)), 
		array("East Beach, Los Angeles", 				array(2632.80, -1668.10, -89.00, 2747.70, -1393.40, 110.90)), 
		array("East Beach, Los Angeles", 				array(2747.70, -1668.10, -89.00, 2959.30, -1498.60, 110.90)), 
		array("East Beach, Los Angeles", 				array(2747.70, -1498.60, -89.00, 2959.30, -1120.00, 110.90)), 
		array("East Los Angeles", 						array(2421.00, -1628.50, -89.00, 2632.80, -1454.30, 110.90)), 
		array("East Los Angeles", 						array(2222.50, -1628.50, -89.00, 2421.00, -1494.00, 110.90)), 
		array("East Los Angeles", 						array(2266.20, -1494.00, -89.00, 2381.60, -1372.00, 110.90)), 
		array("East Los Angeles", 						array(2381.60, -1494.00, -89.00, 2421.00, -1454.30, 110.90)), 
		array("East Los Angeles", 						array(2281.40, -1372.00, -89.00, 2381.60, -1135.00, 110.90)), 
		array("East Los Angeles", 						array(2381.60, -1454.30, -89.00, 2462.10, -1135.00, 110.90)), 
		array("East Los Angeles", 						array(2462.10, -1454.30, -89.00, 2581.70, -1135.00, 110.90)), 
		array("Easter Basin, San Fierro", 				array(-1794.90, 249.90, -9.10, -1242.90, 578.30, 200.00)), 
		array("Easter Basin, San Fierro", 				array(-1794.90, -50.00, -0.00, -1499.80, 249.90, 200.00)), 
		array("Easter Bay Airport, San Fierro", 		array(-1499.80, -50.00, -0.00, -1242.90, 249.90, 200.00)), 
		array("Easter Bay Airport, San Fierro", 		array(-1794.90, -730.10, -3.00, -1213.90, -50.00, 200.00)), 
		array("Easter Bay Airport, San Fierro", 		array(-1213.90, -730.10, 0.00, -1132.80, -50.00, 200.00)), 
		array("Easter Bay Airport, San Fierro", 		array(-1242.90, -50.00, 0.00, -1213.90, 578.30, 200.00)), 
		array("Easter Bay Airport, San Fierro", 		array(-1213.90, -50.00, -4.50, -947.90, 578.30, 200.00)), 
		array("Easter Bay Airport, San Fierro", 		array(-1315.40, -405.30, 15.40, -1264.40, -209.50, 25.40)), 
		array("Easter Bay Airport, San Fierro", 		array(-1354.30, -287.30, 15.40, -1315.40, -209.50, 25.40)), 
		array("Easter Bay Airport, San Fierro", 		array(-1490.30, -209.50, 15.40, -1264.40, -148.30, 25.40)), 
		array("Easter Bay Chemical, San Fierro", 		array(-1132.80, -768.00, 0.00, -956.40, -578.10, 200.00)), 
		array("Easter Bay Chemical, San Fierro", 		array(-1132.80, -787.30, 0.00, -956.40, -768.00, 200.00)), 
		array("Castillo del Diablo, Bony County", 		array(-464.50, 2217.60, 0.00, -208.50, 2580.30, 200.00)), 
		array("Castillo del Diablo, Bony County", 		array(-208.50, 2123.00, -7.60, 114.00, 2337.10, 200.00)), 
		array("Castillo del Diablo, Bony County", 		array(-208.50, 2337.10, 0.00, 8.40, 2487.10, 200.00)), 
		array("El Corona, Los Angeles", 				array(1812.60, -2179.20, -89.00, 1970.60, -1852.80, 110.90)), 
		array("El Corona, Los Angeles", 				array(1692.60, -2179.20, -89.00, 1812.60, -1842.20, 110.90)), 
		array("El Quebrados, Tierra Robada", 			array(-1645.20, 2498.50, 0.00, -1372.10, 2777.80, 200.00)), 
		array("Esplanade East, San Fierro", 			array(-1620.30, 1176.50, -4.50, -1580.00, 1274.20, 200.00)), 
		array("Esplanade East, San Fierro", 			array(-1580.00, 1025.90, -6.10, -1499.80, 1274.20, 200.00)), 
		array("Esplanade East, San Fierro", 			array(-1499.80, 578.30, -79.60, -1339.80, 1274.20, 20.30)), 
		array("Esplanade North, San Fierro", 			array(-2533.00, 1358.90, -4.50, -1996.60, 1501.20, 200.00)), 
		array("Esplanade North, San Fierro", 			array(-1996.60, 1358.90, -4.50, -1524.20, 1592.50, 200.00)), 
		array("Esplanade North, San Fierro", 			array(-1982.30, 1274.20, -4.50, -1524.20, 1358.90, 200.00)), 
		array("Fallen Tree, Red County", 				array(-792.20, -698.50, -5.30, -452.40, -380.00, 200.00)), 
		array("Fallow Bridge, Red County", 				array(434.30, 366.50, 0.00, 603.00, 555.60, 200.00)), 
		array("Fern Ridge, Red County", 				array(508.10, -139.20, 0.00, 1306.60, 119.50, 200.00)), 
		array("Financial, San Fierro", 					array(-1871.70, 744.10, -6.10, -1701.30, 1176.40, 300.00)), 
		array("Fisher, s Lagoon, Red County", 			array(1916.90, -233.30, -100.00, 2131.70, 13.80, 200.00)), 
		array("Flint Intersection, Flint County", 		array(-187.70, -1596.70, -89.00, 17.00, -1276.60, 110.90)), 
		array("Flint Range, Flint County", 				array(-594.10, -1648.50, 0.00, -187.70, -1276.60, 200.00)), 
		array("Fort Carson, Tierra Robada", 			array(-376.20, 826.30, -3.00, 123.70, 1220.40, 200.00)), 
		array("Foster Valley, San Fierro", 				array(-2270.00, -430.20, -0.00, -2178.60, -324.10, 200.00)), 
		array("Foster Valley, San Fierro", 				array(-2178.60, -599.80, -0.00, -1794.90, -324.10, 200.00)), 
		array("Foster Valley, San Fierro", 				array(-2178.60, -1115.50, 0.00, -1794.90, -599.80, 200.00)), 
		array("Foster Valley, San Fierro", 				array(-2178.60, -1250.90, 0.00, -1794.90, -1115.50, 200.00)), 
		array("Frederick Bridge, Red County", 			array(2759.20, 296.50, 0.00, 2774.20, 594.70, 200.00)), 
		array("Gant Bridge, San Fierro", 				array(-2741.40, 1659.60, -6.10, -2616.40, 2175.10, 200.00)), 
		array("Gant Bridge, San Fierro", 				array(-2741.00, 1490.40, -6.10, -2616.40, 1659.60, 200.00)), 
		array("Ganton, Los Angeles", 					array(2222.50, -1852.80, -89.00, 2632.80, -1722.30, 110.90)), 
		array("Ganton, Los Angeles", 					array(2222.50, -1722.30, -89.00, 2632.80, -1628.50, 110.90)), 
		array("Garcia, San Fierro", 					array(-2411.20, -222.50, -0.00, -2173.00, 265.20, 200.00)), 
		array("Garcia, San Fierro", 					array(-2395.10, -222.50, -5.30, -2354.00, -204.70, 200.00)), 
		array("Garver Bridge, San Fierro", 				array(-1339.80, 828.10, -89.00, -1213.90, 1057.00, 110.90)), 
		array("Garver Bridge, San Fierro", 				array(-1213.90, 950.00, -89.00, -1087.90, 1178.90, 110.90)), 
		array("Garver Bridge, San Fierro", 				array(-1499.80, 696.40, -179.60, -1339.80, 925.30, 20.30)), 
		array("Glen Park, Los Angeles", 				array(1812.60, -1449.60, -89.00, 1996.90, -1350.70, 110.90)), 
		array("Glen Park, Los Angeles", 				array(1812.60, -1100.80, -89.00, 1994.30, -973.30, 110.90)), 
		array("Glen Park, Los Angeles", 				array(1812.60, -1350.70, -89.00, 2056.80, -1100.80, 110.90)), 
		array("Green Palms, Bone County", 				array(176.50, 1305.40, -3.00, 338.60, 1520.70, 200.00)), 
		array("Greenglass College, Las Venturas", 		array(964.30, 1044.60, -89.00, 1197.30, 1203.20, 110.90)), 
		array("Greenglass College, Las Venturas", 		array(964.30, 930.80, -89.00, 1166.50, 1044.60, 110.90)), 
		array("Hampton Barns, Red County", 				array(603.00, 264.30, 0.00, 761.90, 366.50, 200.00)), 
		array("Hankypanky Point, Red County", 			array(2576.90, 62.10, 0.00, 2759.20, 385.50, 200.00)), 
		array("Harry Gold Parkway, Las Venturas", 		array(1777.30, 863.20, -89.00, 1817.30, 2342.80, 110.90)), 
		array("Hashbury, San Fierro", 					array(-2593.40, -222.50, -0.00, -2411.20, 54.70, 200.00)), 
		array("Hilltop Farm, San Fierro", 				array(967.30, -450.30, -3.00, 1176.70, -217.90, 200.00)), 
		array("Hunter Quarry, Las Venturas", 			array(337.20, 710.80, -115.20, 860.50, 1031.70, 203.70)), 
		array("Idlewood, Los Angeles", 					array(1812.60, -1852.80, -89.00, 1971.60, -1742.30, 110.90)), 
		array("Idlewood, Los Angeles", 					array(1812.60, -1742.30, -89.00, 1951.60, -1602.30, 110.90)), 
		array("Idlewood, Los Angeles", 					array(1951.60, -1742.30, -89.00, 2124.60, -1602.30, 110.90)), 
		array("Idlewood, Los Angeles", 					array(1812.60, -1602.30, -89.00, 2124.60, -1449.60, 110.90)), 
		array("Idlewood, Los Angeles", 					array(2124.60, -1742.30, -89.00, 2222.50, -1494.00, 110.90)), 
		array("Idlewood, Los Angeles", 					array(1971.60, -1852.80, -89.00, 2222.50, -1742.30, 110.90)), 
		array("Jefferson, Los Angeles", 				array(1996.90, -1449.60, -89.00, 2056.80, -1350.70, 110.90)), 
		array("Jefferson, Los Angeles", 				array(2124.60, -1494.00, -89.00, 2266.20, -1449.60, 110.90)), 
		array("Jefferson, Los Angeles", 				array(2056.80, -1372.00, -89.00, 2281.40, -1210.70, 110.90)), 
		array("Jefferson, Los Angeles", 				array(2056.80, -1210.70, -89.00, 2185.30, -1126.30, 110.90)), 
		array("Jefferson, Los Angeles", 				array(2185.30, -1210.70, -89.00, 2281.40, -1154.50, 110.90)), 
		array("Jefferson, Los Angeles", 				array(2056.80, -1449.60, -89.00, 2266.20, -1372.00, 110.90)), 
		array("Julius Thruway East, Las Venturas", 		array(2623.10, 943.20, -89.00, 2749.90, 1055.90, 110.90)), 
		array("Julius Thruway East, Las Venturas", 		array(2685.10, 1055.90, -89.00, 2749.90, 2626.50, 110.90)), 
		array("Julius Thruway East, Las Venturas", 		array(2536.40, 2442.50, -89.00, 2685.10, 2542.50, 110.90)), 
		array("Julius Thruway East, Las Venturas", 		array(2625.10, 2202.70, -89.00, 2685.10, 2442.50, 110.90)), 
		array("Julius Thruway North, Las Venturas", 	array(2498.20, 2542.50, -89.00, 2685.10, 2626.50, 110.90)), 
		array("Julius Thruway North, Las Venturas", 	array(2237.40, 2542.50, -89.00, 2498.20, 2663.10, 110.90)), 
		array("Julius Thruway North, Las Venturas", 	array(2121.40, 2508.20, -89.00, 2237.40, 2663.10, 110.90)), 
		array("Julius Thruway North, Las Venturas", 	array(1938.80, 2508.20, -89.00, 2121.40, 2624.20, 110.90)), 
		array("Julius Thruway North, Las Venturas", 	array(1534.50, 2433.20, -89.00, 1848.40, 2583.20, 110.90)), 
		array("Julius Thruway North, Las Venturas", 	array(1848.40, 2478.40, -89.00, 1938.80, 2553.40, 110.90)), 
		array("Julius Thruway North, Las Venturas", 	array(1704.50, 2342.80, -89.00, 1848.40, 2433.20, 110.90)), 
		array("Julius Thruway North, Las Venturas", 	array(1377.30, 2433.20, -89.00, 1534.50, 2507.20, 110.90)), 
		array("Julius Thruway South, Las Venturas", 	array(1457.30, 823.20, -89.00, 2377.30, 863.20, 110.90)), 
		array("Julius Thruway South, Las Venturas", 	array(2377.30, 788.80, -89.00, 2537.30, 897.90, 110.90)), 
		array("Julius Thruway West, Las Venturas", 		array(1197.30, 1163.30, -89.00, 1236.60, 2243.20, 110.90)), 
		array("Julius Thruway West, Las Venturas", 		array(1236.60, 2142.80, -89.00, 1297.40, 2243.20, 110.90)), 
		array("Juniper Hill, San Fierro", 				array(-2533.00, 578.30, -7.60, -2274.10, 968.30, 200.00)), 
		array("Juniper Hollow, San Fierro", 			array(-2533.00, 968.30, -6.10, -2274.10, 1358.90, 200.00)), 
		array("KACC Military Fuels, Las Venturas", 		array(2498.20, 2626.50, -89.00, 2749.90, 2861.50, 110.90)), 
		array("Kincaid Bridge, San Fierro", 			array(-1339.80, 599.20, -89.00, -1213.90, 828.10, 110.90)), 
		array("Kincaid Bridge, San Fierro", 			array(-1213.90, 721.10, -89.00, -1087.90, 950.00, 110.90)), 
		array("Kincaid Bridge, San Fierro", 			array(-1087.90, 855.30, -89.00, -961.90, 986.20, 110.90)), 
		array("King, s, San Fierro", 					array(-2329.30, 458.40, -7.60, -1993.20, 578.30, 200.00)), 
		array("King, s, San Fierro", 					array(-2411.20, 265.20, -9.10, -1993.20, 373.50, 200.00)), 
		array("King, s, San Fierro", 					array(-2253.50, 373.50, -9.10, -1993.20, 458.40, 200.00)), 
		array("LVA Freight Depot, Las Venturas", 		array(1457.30, 863.20, -89.00, 1777.40, 1143.20, 110.90)), 
		array("LVA Freight Depot, Las Venturas", 		array(1375.60, 919.40, -89.00, 1457.30, 1203.20, 110.90)), 
		array("LVA Freight Depot, Las Venturas", 		array(1277.00, 1087.60, -89.00, 1375.60, 1203.20, 110.90)), 
		array("LVA Freight Depot, Las Venturas", 		array(1315.30, 1044.60, -89.00, 1375.60, 1087.60, 110.90)), 
		array("LVA Freight Depot, Las Venturas", 		array(1236.60, 1163.40, -89.00, 1277.00, 1203.20, 110.90)), 
		array("Las Barrancas, Tierra Robada", 			array(-926.10, 1398.70, -3.00, -719.20, 1634.60, 200.00)), 
		array("Las Brujas, Tierra Robada", 				array(-365.10, 2123.00, -3.00, -208.50, 2217.60, 200.00)), 
		array("Las Colinas, Los Angeles", 				array(1994.30, -1100.80, -89.00, 2056.80, -920.80, 110.90)), 
		array("Las Colinas, Los Angeles", 				array(2056.80, -1126.30, -89.00, 2126.80, -920.80, 110.90)), 
		array("Las Colinas, Los Angeles", 				array(2185.30, -1154.50, -89.00, 2281.40, -934.40, 110.90)), 
		array("Las Colinas, Los Angeles", 				array(2126.80, -1126.30, -89.00, 2185.30, -934.40, 110.90)), 
		array("Las Colinas, Los Angeles", 				array(2747.70, -1120.00, -89.00, 2959.30, -945.00, 110.90)), 
		array("Las Colinas, Los Angeles", 				array(2632.70, -1135.00, -89.00, 2747.70, -945.00, 110.90)), 
		array("Las Colinas, Los Angeles", 				array(2281.40, -1135.00, -89.00, 2632.70, -945.00, 110.90)), 
		array("Las Payasadas, Tierra Robada", 			array(-354.30, 2580.30, 2.00, -133.60, 2816.80, 200.00)), 
		array("Las Venturas Airport, Las Venturas", 	array(1236.60, 1203.20, -89.00, 1457.30, 1883.10, 110.90)), 
		array("Las Venturas Airport, Las Venturas", 	array(1457.30, 1203.20, -89.00, 1777.30, 1883.10, 110.90)), 
		array("Las Venturas Airport, Las Venturas", 	array(1457.30, 1143.20, -89.00, 1777.40, 1203.20, 110.90)), 
		array("Las Venturas Airport, Las Venturas", 	array(1515.80, 1586.40, -12.50, 1729.90, 1714.50, 87.50)), 
		array("Last Dime Motel, Las Venturas", 			array(1823.00, 596.30, -89.00, 1997.20, 823.20, 110.90)), 
		array("Leafy Hollow, Las Venturas", 			array(-1166.90, -1856.00, 0.00, -815.60, -1602.00, 200.00)), 
		array("Liberty City", 							array(-1000.00, 400.00, 1300.00, -700.00, 600.00, 1400.00)), 
		array("Lil, Probe Inn, Bone County", 			array(-90.20, 1286.80, -3.00, 153.80, 1554.10, 200.00)), 
		array("Linden Side, Las Venturas", 				array(2749.90, 943.20, -89.00, 2923.30, 1198.90, 110.90)), 
		array("Linden Station, Las Venturas", 			array(2749.90, 1198.90, -89.00, 2923.30, 1548.90, 110.90)), 
		array("Linden Station, Las Venturas", 		 	array(2811.20, 1229.50, -39.50, 2861.20, 1407.50, 60.40)), 
		array("Little Mexico, Los Angeles", 			array(1701.90, -1842.20, -89.00, 1812.60, -1722.20, 110.90)), 
		array("Little Mexico, Los Angeles", 			array(1758.90, -1722.20, -89.00, 1812.60, -1577.50, 110.90)), 
		array("Los Flores, San Fierro", 				array(2581.70, -1454.30, -89.00, 2632.80, -1393.40, 110.90)), 
		array("Los Flores, San Fierro", 				array(2581.70, -1393.40, -89.00, 2747.70, -1135.00, 110.90)), 
		array("LS International, Los Angeles", 			array(1249.60, -2394.30, -89.00, 1852.00, -2179.20, 110.90)), 
		array("LS International, Los Angeles", 			array(1852.00, -2394.30, -89.00, 2089.00, -2179.20, 110.90)), 
		array("LS International, Los Angeles", 			array(1382.70, -2730.80, -89.00, 2201.80, -2394.30, 110.90)), 
		array("LS International, Los Angeles", 			array(1974.60, -2394.30, -39.00, 2089.00, -2256.50, 60.90)), 
		array("LS International, Los Angeles", 			array(1400.90, -2669.20, -39.00, 2189.80, -2597.20, 60.90)), 
		array("LS International, Los Angeles", 			array(2051.60, -2597.20, -39.00, 2152.40, -2394.30, 60.90)), 
		array("Marina, Los Angeles", 					array(647.70, -1804.20, -89.00, 851.40, -1577.50, 110.90)), 
		array("Marina, Los Angeles", 					array(647.70, -1577.50, -89.00, 807.90, -1416.20, 110.90)), 
		array("Marina, Los Angeles", 					array(807.90, -1577.50, -89.00, 926.90, -1416.20, 110.90)), 
		array("Market, Los Angeles", 					array(787.40, -1416.20, -89.00, 1072.60, -1310.20, 110.90)), 
		array("Market, Los Angeles", 					array(952.60, -1310.20, -89.00, 1072.60, -1130.80, 110.90)), 
		array("Market, Los Angeles", 					array(1072.60, -1416.20, -89.00, 1370.80, -1130.80, 110.90)), 
		array("Market, Los Angeles", 					array(926.90, -1577.50, -89.00, 1370.80, -1416.20, 110.90)), 
		array("Market Station, Los Angeles", 			array(787.40, -1410.90, -34.10, 866.00, -1310.20, 65.80)), 
		array("Martin Bridge, Red County", 				array(-222.10, 293.30, 0.00, -122.10, 476.40, 200.00)), 
		array("Missionary Hill, San Fierro", 			array(-2994.40, -811.20, 0.00, -2178.60, -430.20, 200.00)), 
		array("Montgomery, Red County", 				array(1119.50, 119.50, -3.00, 1451.40, 493.30, 200.00)), 
		array("Montgomery, Red County", 				array(1451.40, 347.40, -6.10, 1582.40, 420.80, 200.00)), 
		array("Montgomery Section, Red County", 		array(1546.60, 208.10, 0.00, 1745.80, 347.40, 200.00)), 
		array("Montgomery Section, Red County", 		array(1582.40, 347.40, 0.00, 1664.60, 401.70, 200.00)), 
		array("Mulholland, Los Angeles", 				array(1414.00, -768.00, -89.00, 1667.60, -452.40, 110.90)), 
		array("Mulholland, Los Angeles", 				array(1281.10, -452.40, -89.00, 1641.10, -290.90, 110.90)), 
		array("Mulholland, Los Angeles", 				array(1269.10, -768.00, -89.00, 1414.00, -452.40, 110.90)), 
		array("Mulholland, Los Angeles", 				array(1357.00, -926.90, -89.00, 1463.90, -768.00, 110.90)), 
		array("Mulholland, Los Angeles", 				array(1318.10, -910.10, -89.00, 1357.00, -768.00, 110.90)), 
		array("Mulholland, Los Angeles", 				array(1169.10, -910.10, -89.00, 1318.10, -768.00, 110.90)), 
		array("Mulholland, Los Angeles", 				array(768.60, -954.60, -89.00, 952.60, -860.60, 110.90)), 
		array("Mulholland, Los Angeles", 				array(687.80, -860.60, -89.00, 911.80, -768.00, 110.90)), 
		array("Mulholland, Los Angeles", 				array(737.50, -768.00, -89.00, 1142.20, -674.80, 110.90)), 
		array("Mulholland, Los Angeles", 				array(1096.40, -910.10, -89.00, 1169.10, -768.00, 110.90)), 
		array("Mulholland, Los Angeles", 				array(952.60, -937.10, -89.00, 1096.40, -860.60, 110.90)), 
		array("Mulholland, Los Angeles", 				array(911.80, -860.60, -89.00, 1096.40, -768.00, 110.90)), 
		array("Mulholland, Los Angeles", 				array(861.00, -674.80, -89.00, 1156.50, -600.80, 110.90)), 
		array("Mulholland Section, Los Angeles", 		array(1463.90, -1150.80, -89.00, 1812.60, -768.00, 110.90)), 
		array("North Rock, Red County", 				array(2285.30, -768.00, 0.00, 2770.50, -269.70, 200.00)), 
		array("Ocean Docks, Los Angeles", 				array(2373.70, -2697.00, -89.00, 2809.20, -2330.40, 110.90)), 
		array("Ocean Docks, Los Angeles", 				array(2201.80, -2418.30, -89.00, 2324.00, -2095.00, 110.90)), 
		array("Ocean Docks, Los Angeles", 				array(2324.00, -2302.30, -89.00, 2703.50, -2145.10, 110.90)), 
		array("Ocean Docks, Los Angeles", 				array(2089.00, -2394.30, -89.00, 2201.80, -2235.80, 110.90)), 
		array("Ocean Docks, Los Angeles", 				array(2201.80, -2730.80, -89.00, 2324.00, -2418.30, 110.90)), 
		array("Ocean Docks, Los Angeles", 				array(2703.50, -2302.30, -89.00, 2959.30, -2126.90, 110.90)), 
		array("Ocean Docks, Los Angeles", 				array(2324.00, -2145.10, -89.00, 2703.50, -2059.20, 110.90)), 
		array("Ocean Flats, San Fierro", 				array(-2994.40, 277.40, -9.10, -2867.80, 458.40, 200.00)), 
		array("Ocean Flats, San Fierro", 				array(-2994.40, -222.50, -0.00, -2593.40, 277.40, 200.00)), 
		array("Ocean Flats, San Fierro", 				array(-2994.40, -430.20, -0.00, -2831.80, -222.50, 200.00)), 
		array("Octane Springs, Bone County", 			array(338.60, 1228.50, 0.00, 664.30, 1655.00, 200.00)), 
		array("Old Venturas Strip, Las Venturas", 		array(2162.30, 2012.10, -89.00, 2685.10, 2202.70, 110.90)), 
		array("Palisades, San Fierro", 					array(-2994.40, 458.40, -6.10, -2741.00, 1339.60, 200.00)), 
		array("Palomino Creek, Red County", 			array(2160.20, -149.00, 0.00, 2576.90, 228.30, 200.00)), 
		array("Paradiso, San Fierro", 					array(-2741.00, 793.40, -6.10, -2533.00, 1268.40, 200.00)), 
		array("Pershing Square, Los Angeles", 			array(1440.90, -1722.20, -89.00, 1583.50, -1577.50, 110.90)), 
		array("Pilgrim, Las Venturas", 					array(2437.30, 1383.20, -89.00, 2624.40, 1783.20, 110.90)), 
		array("Pilgrim, Las Venturas", 					array(2624.40, 1383.20, -89.00, 2685.10, 1783.20, 110.90)), 
		array("Pilson Intersection, Las Venturas", 		array(1098.30, 2243.20, -89.00, 1377.30, 2507.20, 110.90)), 
		array("Pirates in Men, s Pants, Las Venturas", 	array(1817.30, 1469.20, -89.00, 2027.40, 1703.20, 110.90)), 
		array("Playa del Seville, Los Angeles", 		array(2703.50, -2126.90, -89.00, 2959.30, -1852.80, 110.90)), 
		array("Prickle Pine, Las Venturas", 			array(1534.50, 2583.20, -89.00, 1848.40, 2863.20, 110.90)), 
		array("Prickle Pine, Las Venturas", 			array(1117.40, 2507.20, -89.00, 1534.50, 2723.20, 110.90)), 
		array("Prickle Pine, Las Venturas", 			array(1848.40, 2553.40, -89.00, 1938.80, 2863.20, 110.90)), 
		array("Prickle Pine, Las Venturas", 			array(1938.80, 2624.20, -89.00, 2121.40, 2861.50, 110.90)), 
		array("Queens,  San Fierro", 					array(-2533.00, 458.40, 0.00, -2329.30, 578.30, 200.00)), 
		array("Queens,  San Fierro", 					array(-2593.40, 54.70, 0.00, -2411.20, 458.40, 200.00)), 
		array("Queens,  San Fierro", 					array(-2411.20, 373.50, 0.00, -2253.50, 458.40, 200.00)), 
		array("Randolph Ind. Estate, Las Venturas", 	array(1558.00, 596.30, -89.00, 1823.00, 823.20, 110.90)), 
		array("Redsands East, Las Venturas", 			array(1817.30, 2011.80, -89.00, 2106.70, 2202.70, 110.90)), 
		array("Redsands East, Las Venturas", 			array(1817.30, 2202.70, -89.00, 2011.90, 2342.80, 110.90)), 
		array("Redsands East, Las Venturas", 			array(1848.40, 2342.80, -89.00, 2011.90, 2478.40, 110.90)), 
		array("Redsands West, Las Venturas", 			array(1236.60, 1883.10, -89.00, 1777.30, 2142.80, 110.90)), 
		array("Redsands West, Las Venturas", 			array(1297.40, 2142.80, -89.00, 1777.30, 2243.20, 110.90)), 
		array("Redsands West, Las Venturas", 			array(1377.30, 2243.20, -89.00, 1704.50, 2433.20, 110.90)), 
		array("Redsands West, Las Venturas", 			array(1704.50, 2243.20, -89.00, 1777.30, 2342.80, 110.90)), 
		array("Regular Tom, Bone County", 				array(-405.70, 1712.80, -3.00, -276.70, 1892.70, 200.00)), 
		array("Richman, Los Angeles", 					array(647.50, -1118.20, -89.00, 787.40, -954.60, 110.90)), 
		array("Richman, Los Angeles", 					array(647.50, -954.60, -89.00, 768.60, -860.60, 110.90)), 
		array("Richman, Los Angeles", 					array(225.10, -1369.60, -89.00, 334.50, -1292.00, 110.90)), 
		array("Richman, Los Angeles", 					array(225.10, -1292.00, -89.00, 466.20, -1235.00, 110.90)), 
		array("Richman, Los Angeles", 					array(72.60, -1404.90, -89.00, 225.10, -1235.00, 110.90)), 
		array("Richman, Los Angeles", 					array(72.60, -1235.00, -89.00, 321.30, -1008.10, 110.90)), 
		array("Richman, Los Angeles", 					array(321.30, -1235.00, -89.00, 647.50, -1044.00, 110.90)), 
		array("Richman, Los Angeles", 					array(321.30, -1044.00, -89.00, 647.50, -860.60, 110.90)), 
		array("Richman, Los Angeles", 					array(321.30, -860.60, -89.00, 687.80, -768.00, 110.90)), 
		array("Richman, Los Angeles", 					array(321.30, -768.00, -89.00, 700.70, -674.80, 110.90)), 
		array("Robada Section, Tierra Robada", 		 	array(-1119.00, 1178.90, -89.00, -862.00, 1351.40, 110.90)), 
		array("Roca Escalante, Las Venturas", 			array(2237.40, 2202.70, -89.00, 2536.40, 2542.50, 110.90)), 
		array("Roca Escalante, Las Venturas", 			array(2536.40, 2202.70, -89.00, 2625.10, 2442.50, 110.90)), 
		array("Rockshore East, Las Venturas", 			array(2537.30, 676.50, -89.00, 2902.30, 943.20, 110.90)), 
		array("Rockshore West, Las Venturas", 			array(1997.20, 596.30, -89.00, 2377.30, 823.20, 110.90)), 
		array("Rockshore West, Las Venturas", 			array(2377.30, 596.30, -89.00, 2537.30, 788.80, 110.90)), 
		array("Rodeo, Los Angeles", 					array(72.60, -1684.60, -89.00, 225.10, -1544.10, 110.90)), 
		array("Rodeo, Los Angeles", 					array(72.60, -1544.10, -89.00, 225.10, -1404.90, 110.90)), 
		array("Rodeo, Los Angeles", 					array(225.10, -1684.60, -89.00, 312.80, -1501.90, 110.90)), 
		array("Rodeo, Los Angeles", 					array(225.10, -1501.90, -89.00, 334.50, -1369.60, 110.90)), 
		array("Rodeo, Los Angeles", 					array(334.50, -1501.90, -89.00, 422.60, -1406.00, 110.90)), 
		array("Rodeo, Los Angeles", 					array(312.80, -1684.60, -89.00, 422.60, -1501.90, 110.90)), 
		array("Rodeo, Los Angeles", 					array(422.60, -1684.60, -89.00, 558.00, -1570.20, 110.90)), 
		array("Rodeo, Los Angeles", 					array(558.00, -1684.60, -89.00, 647.50, -1384.90, 110.90)), 
		array("Rodeo, Los Angeles", 					array(466.20, -1570.20, -89.00, 558.00, -1385.00, 110.90)), 
		array("Rodeo, Los Angeles", 					array(422.60, -1570.20, -89.00, 466.20, -1406.00, 110.90)), 
		array("Rodeo, Los Angeles", 					array(466.20, -1385.00, -89.00, 647.50, -1235.00, 110.90)), 
		array("Rodeo, Los Angeles", 					array(334.50, -1406.00, -89.00, 466.20, -1292.00, 110.90)), 
		array("Royal Casino, Las Venturas", 			array(2087.30, 1383.20, -89.00, 2437.30, 1543.20, 110.90)), 
		array("San Andreas Sound", 		   				array(2450.30, 385.50, -100.00, 2759.20, 562.30, 200.00)), 
		array("Santa Flora, San Fierro", 				array(-2741.00, 458.40, -7.60, -2533.00, 793.40, 200.00)), 
		array("Santa Maria Beach, Los Angeles", 		array(342.60, -2173.20, -89.00, 647.70, -1684.60, 110.90)), 
		array("Santa Maria Beach, Los Angeles", 		array(72.60, -2173.20, -89.00, 342.60, -1684.60, 110.90)), 
		array("Shady Cabin, Whetstone", 				array(-1632.80, -2263.40, -3.00, -1601.30, -2231.70, 200.00)), 
		array("Shady Creeks, Whetstone", 				array(-1820.60, -2643.60, -8.00, -1226.70, -1771.60, 200.00)), 
		array("Shady Creeks, Whetstone", 				array(-2030.10, -2174.80, -6.10, -1820.60, -1771.60, 200.00)), 
		array("Sobell Rail Yards, Las Venturas", 		array(2749.90, 1548.90, -89.00, 2923.30, 1937.20, 110.90)), 
		array("Spinybed, Las Venturas", 				array(2121.40, 2663.10, -89.00, 2498.20, 2861.50, 110.90)), 
		array("Starfish Casino, Las Venturas", 			array(2437.30, 1783.20, -89.00, 2685.10, 2012.10, 110.90)), 
		array("Starfish Casino, Las Venturas", 			array(2437.30, 1858.10, -39.00, 2495.00, 1970.80, 60.90)), 
		array("Starfish Casino, Las Venturas", 			array(2162.30, 1883.20, -89.00, 2437.30, 2012.10, 110.90)), 
		array("Temple, Los Angeles", 					array(1252.30, -1130.80, -89.00, 1378.30, -1026.30, 110.90)), 
		array("Temple, Los Angeles", 					array(1252.30, -1026.30, -89.00, 1391.00, -926.90, 110.90)), 
		array("Temple, Los Angeles", 					array(1252.30, -926.90, -89.00, 1357.00, -910.10, 110.90)), 
		array("Temple, Los Angeles", 					array(952.60, -1130.80, -89.00, 1096.40, -937.10, 110.90)), 
		array("Temple, Los Angeles", 					array(1096.40, -1130.80, -89.00, 1252.30, -1026.30, 110.90)), 
		array("Temple, Los Angeles", 					array(1096.40, -1026.30, -89.00, 1252.30, -910.10, 110.90)), 
		array("The Camel, s Toe, Las Venturas", 		array(2087.30, 1203.20, -89.00, 2640.40, 1383.20, 110.90)), 
		array("The Clown, s Pocket, Las Venturas", 		array(2162.30, 1783.20, -89.00, 2437.30, 1883.20, 110.90)), 
		array("The Emerald Isle, Las Venturas", 		array(2011.90, 2202.70, -89.00, 2237.40, 2508.20, 110.90)), 
		array("The Farm, Flint County", 				array(-1209.60, -1317.10, 114.90, -908.10, -787.30, 251.90)), 
		array("Four Dragons Casino, Las Venturas", 		array(1817.30, 863.20, -89.00, 2027.30, 1083.20, 110.90)), 
		array("The High Roller, Las Venturas", 			array(1817.30, 1283.20, -89.00, 2027.30, 1469.20, 110.90)), 
		array("The Mako Span, Las Venturas", 			array(1664.60, 401.70, 0.00, 1785.10, 567.20, 200.00)), 
		array("The Panopticon, Whetstone", 				array(-947.90, -304.30, -1.10, -319.60, 327.00, 200.00)), 
		array("The Pink Swan, Red County", 			 	array(1817.30, 1083.20, -89.00, 2027.30, 1283.20, 110.90)), 
		array("The Sherman Dam, Las Venturas", 			array(-968.70, 1929.40, -3.00, -481.10, 2155.20, 200.00)), 
		array("The Strip, Las Venturas", 				array(2027.40, 863.20, -89.00, 2087.30, 1703.20, 110.90)), 
		array("The Strip, Las Venturas", 				array(2106.70, 1863.20, -89.00, 2162.30, 2202.70, 110.90)), 
		array("The Strip, Las Venturas", 				array(2027.40, 1783.20, -89.00, 2162.30, 1863.20, 110.90)), 
		array("The Strip, Las Venturas", 				array(2027.40, 1703.20, -89.00, 2137.40, 1783.20, 110.90)), 
		array("The Visage, Las Venturas", 				array(1817.30, 1863.20, -89.00, 2106.70, 2011.80, 110.90)), 
		array("The Visage, Las Venturas", 				array(1817.30, 1703.20, -89.00, 2027.40, 1863.20, 110.90)), 
		array("Unity Station, Los Angeles", 			array(1692.60, -1971.80, -20.40, 1812.60, -1932.80, 79.50)), 
		array("Valle Ocultado, Los Angeles", 			array(-936.60, 2611.40, 2.00, -715.90, 2847.90, 200.00)), 
		array("Verdant Bluffs, Los Angeles", 			array(930.20, -2488.40, -89.00, 1249.60, -2006.70, 110.90)), 
		array("Verdant Bluffs, Los Angeles", 			array(1073.20, -2006.70, -89.00, 1249.60, -1842.20, 110.90)), 
		array("Verdant Bluffs, Los Angeles", 			array(1249.60, -2179.20, -89.00, 1692.60, -1842.20, 110.90)), 
		array("Verdant Meadows, Bone County", 			array(37.00, 2337.10, -3.00, 435.90, 2677.90, 200.00)), 
		array("Verona Beach, Los Angeles", 				array(647.70, -2173.20, -89.00, 930.20, -1804.20, 110.90)), 
		array("Verona Beach, Los Angeles", 				array(930.20, -2006.70, -89.00, 1073.20, -1804.20, 110.90)), 
		array("Verona Beach, Los Angeles", 				array(851.40, -1804.20, -89.00, 1046.10, -1577.50, 110.90)), 
		array("Verona Beach, Los Angeles", 				array(1161.50, -1722.20, -89.00, 1323.90, -1577.50, 110.90)), 
		array("Verona Beach, Los Angeles", 				array(1046.10, -1722.20, -89.00, 1161.50, -1577.50, 110.90)), 
		array("Vinewood, Los Angeles", 					array(787.40, -1310.20, -89.00, 952.60, -1130.80, 110.90)), 
		array("Vinewood, Los Angeles", 					array(787.40, -1130.80, -89.00, 952.60, -954.60, 110.90)), 
		array("Vinewood, Los Angeles", 					array(647.50, -1227.20, -89.00, 787.40, -1118.20, 110.90)), 
		array("Vinewood, Los Angeles", 					array(647.70, -1416.20, -89.00, 787.40, -1227.20, 110.90)), 
		array("Whitewood Estates, Las Venturas", 		array(883.30, 1726.20, -89.00, 1098.30, 2507.20, 110.90)), 
		array("Whitewood Estates, Las Venturas", 		array(1098.30, 1726.20, -89.00, 1197.30, 2243.20, 110.90)), 
		array("Willowfield, Los Angeles", 				array(1970.60, -2179.20, -89.00, 2089.00, -1852.80, 110.90)), 
		array("Willowfield, Los Angeles", 				array(2089.00, -2235.80, -89.00, 2201.80, -1989.90, 110.90)), 
		array("Willowfield, Los Angeles", 				array(2089.00, -1989.90, -89.00, 2324.00, -1852.80, 110.90)), 
		array("Willowfield, Los Angeles", 				array(2201.80, -2095.00, -89.00, 2324.00, -1989.90, 110.90)), 
		array("Willowfield, Los Angeles", 				array(2541.70, -1941.40, -89.00, 2703.50, -1852.80, 110.90)), 
		array("Willowfield, Los Angeles", 				array(2324.00, -2059.20, -89.00, 2541.70, -1852.80, 110.90)), 
		array("Willowfield, Los Angeles", 				array(2541.70, -2059.20, -89.00, 2703.50, -1941.40, 110.90)), 
		array("Yellow Bell Station, Las Venturas", 		array(1377.40, 2600.40, -21.90, 1492.40, 2687.30, 78.00)), 
		// Main Zones
		array("Bone County", 							array(-480.50, 596.30, -242.90, 869.40, 2993.80, 900.00)), 
		array("Tierra Robada", 							array(-2997.40, 1659.60, -242.90, -480.50, 2993.80, 900.00)), 
		array("Tierra Robada", 							array(-1213.90, 596.30, -242.90, -480.50, 1659.60, 900.00)), 
		array("Red County", 							array(-1213.90, -768.00, -242.90, 2997.00, 596.30, 900.00)), 
		array("Flint County", 							array(-1213.90, -2892.90, -242.90, 44.60, -768.00, 900.00)), 
		array("Whetstone", 								array(-2997.40, -2892.90, -242.90, -1213.90, -1115.50, 900.00)), 
		array("Los Angeles", 							array(44.60, -2892.90, -242.90, 2997.00, -768.00, 900.00)), 
		array("Las Venturas",							array(869.40, 596.30, -242.90, 2997.00, 2993.80, 900.00)), 
		array("San Fierro", 							array(-2997.40, -1115.50, -242.90, -1213.90, 1659.60, 900.00))
	);

	$position = explode(',', $position);
	$x = $position[0];
	$y = $position[1];
	$interior = $position[4];
	$virtualworld = $position[5];

	if($interior != 0 || $virtualworld != 0)
		return "추적불가";
	foreach ($zones as $value) {
		if(($x >= $value[1][0]) && ($x <= $value[1][3]) && ($y >= $value[1][1]) && ($y <= $value[1][4])) {
			return $value[0];
		}
	}
	return "추적불가";
}
?>