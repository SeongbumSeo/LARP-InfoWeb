<?php
session_start();

require_once('../config.php');
require_once('mysqli.php');
require_once('getlocationname.php');

if(!isset($_SESSION['id'])) {
	print("Session Error");
	exit;
}

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];

$presult = $mysqli->query("
	SELECT
		a.*,
		IF(
			b.Name != \"\",
			b.Name,
			\"미가입\"
		) FactionName,
		d.Title Party
	FROM
		".DB_LARP.".user_data a
	LEFT OUTER JOIN
		".DB_LARP.".faction_data b
		ON b.ID = a.Faction
	LEFT OUTER JOIN
		".DB_LARP.".party_members c
		ON c.UserID = a.ID
	LEFT OUTER JOIN
		".DB_LARP.".party_list d
		ON d.ID = c.PartyID 
	WHERE
		a.ID = $id
		AND a.Password = '$password'
	ORDER BY a.CreatedTime ASC
	LIMIT 1");
$cresult = $mysqli->query("
	SELECT
		a.*,
		c.Name ParkingLot
	FROM
		".DB_LARP.".car_data a
	INNER JOIN
		".DB_LARP.".user_data b
		ON b.ID = a.OwnerID
	LEFT OUTER JOIN
		".DB_LARP.".parkinglot_data c
		ON c.ID = SUBSTRING_INDEX(a.LastPos, ',', -1)-40000
	WHERE
		a.OwnerType = 1
		AND b.ID = $id
	ORDER BY a.CreatedTime ASC");

$contents = array();
if($data = $presult->fetch_array()) {
	$pnumber = $data['PhoneNumber'] == 0 ? "없음" : $data['PhoneNumber'];
	$origins = Array("미국", "한국", "이탈리아", "일본", "스페인", "러시아", "프랑스", "중국", "이라크", "독일", "영국");
	$origin = $data['Origin'] < 0 || $data['Origin'] > count($origins) ? "알수없음" : $origins[$data['Origin']];
	$money = '$'.number_format($data['Money']);
	$bank = '$'.number_format($data['Bank']);
	$bankbook = sprintf("%03d-%03d", $data['Bankbook']/1000, $data['Bankbook']%1000);
	$position = explode(',', $data['LastPos']);
	$trackable = $position[4] != 0 || $position[5] != 0 ? 0 : 1;
	$position = $trackable == 1 ? $position : array(0, 0);

	$contents['Player'] = array(
		'Username' => str_replace('_', ' ', $data['Username']),
		'Level' => $data['Level'],
		'Party' => $data['Party'],
		'Skin' => $data['Skin'],
		'Health' => $data['Health'],
		'Hunger' => $data['Hunger'],
		'PositionX' => $position[0],
		'PositionY' => $position[1],
		'Trackable' => $trackable,

		'Age' => addData("나이", $data['Age']),
		'PhoneNumber' => addData("전화번호", $pnumber),
		'Origin' => addData("국적", $origin),

		'Money' => addData("소지금", $money),
		'Bank' => addData("은행", $bank),
		'Bankbook' => addData("계좌번호", $bankbook),

		'FactionName' => addData("팩션", $data['FactionName']),
		'Job' => addData("직업", getJobName($data['Job'])),

		'Warns' => addData("경고", $data['Warns']."/7"),
		'Praises' => addData("칭찬", $data['Praises']."/3"),

		'Location' => addData("위치", getLocationName($position[0], $position[1], $position[4], $position[5]), "white-space: normal;")
	);

	unset($data);
	
	$i = 0;
	while($data = $cresult->fetch_array()) {
		$model = getVehicleModelName($data['Model']);
		$caption = sprintf("%s <span>(%s)</span>", $model, $data['NumberPlate']);
		$position = explode(',', $data['LastPos']);
		$trackable = $data['GPS'] == 0 ? 2 : $position[4] != 0 || $position[5] != 0 ? 3 : 1;
		$position = $trackable == 1 ? $position : array(0, 0);
		if($data['Towed'])
			$location = "<u>견인 차량 보관소</u>";
		else if($data['Blowed'])
			$location = "<u>파괴 차량 보관소</u>";
		else if($data['ParkingLot'] != null)
			$location = $data['ParkingLot'];
		else
			$location = getLocationName($position[0], $position[1], $position[4], $position[5]);

		$contents['Vehicle'][$i] = array(
			'ID' => $data['ID'],
			'Caption' => $caption,
			'Modelname' => $model,
			'Model' => $data['Model'],
			'Health' => $data['Health'] / 10,
			'Fuel' => $data['Fuel'] / 100000,
			'PositionX' => $position[0],
			'PositionY' => $position[1],
			'Trackable' => $trackable,

			'NumberPlate' => addData("번호판", $data['NumberPlate']),
			'Engine' => addData("시동", $data['Engine'] ? "켜져있음" : "꺼져있음"),
			'Active' => addData("상태", $data['Active'] ? "꺼내져있음" : "넣어져있음"),
			'Locked' => addData("잠금여부", $data['Locked'] ? "잠겨있음" : "열려있음"),
			'BlowedCnt' => addData("블로우", $data['BlowedCnt']."회"),
			'Location' => addData("위치", $location, "white-space: normal;")
		);

		unset($data);
		$i++;
	}
	print(json_encode($contents));
}
else
	print("No Data");

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
?>