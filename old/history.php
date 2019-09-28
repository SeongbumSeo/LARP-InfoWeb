<?php
include "./initial.inc.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
		<?php
include "./head.inc.php";
		?>
		<title>History - LA:RP Information Website</title>
		<script type="text/javascript">
			$(document).ready(function() {
				var i = 0;
				$(".navl_history").addClass("active");
				$("#conbox.head").each(function() {
					if(i == 0)
						$(this).css("background-color", "#DDD");
					else
						$(this).css("background-color", "#AAA");
					i++;
				});
				$("#conbox.body").each(function() {
					$(this).css("background-color", "#FFF");
				});
			});
		</script>
	</head>

	<body>
		<?php
include "./navbar.inc.php";
		?>
		<div id="contents">
			<?php
include "./header.inc.php";
			?>

			<div id="conbox" class="head">
				<h1>2017년 1월 16일</h1>
			</div>
			<div id="conbox" class="body">
                <p>- 인포웹2가 베타 오픈하였습니다.</p>
                <p>- 구버전 인포웹은 더이상 업데이트하지 않습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 6월 2일</h1>
			</div>
			<div id="conbox" class="body">
                <p>- Home 탭을 추가하였습니다. Home 탭에서는 공지사항과 이벤트를 확인하실 수 있습니다.</p>
                <p>- Home 탭에 피드백 키트가 추가되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 5월 26일</h1>
			</div>
			<div id="conbox" class="body">
                <p>- 벌점이 자격증 별로 따로 표시되도록 하였습니다.</p>
                <p>- Admin 탭에 킬 로그 조회 키트를 추가했습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 5월 25일</h1>
			</div>
			<div id="conbox" class="body">
                <p>- 프로세스 로그 키트를 삭제했습니다.</p>
                <p>- Admin 탭에 메일주소 조회 키트를 추가했습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 3월 8일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 서버 상태가 표시되지 않던 오류를 수정했습니다.</p>
				<p>- 회원가입 로직 수정 작업으로 인해 당분간 인포웹을 통한 회원가입이 불가능합니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 2월 22일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 이제 인포웹에서 회원가입을 할 수 있습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 2월 20일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- Profile 탭의 아이템 키트와 프로세스 로그 키트가 임시 제거 되었습니다.</p>
				<p>- 이제 모바일에서도 사이즈 부담 없이 인포웹을 이용하실 수 있습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 2월 3일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 2월 3일 이후 오프라인 프로세스를 통해 받은 경고/차감/칭찬 내역도 Profile 탭 사용자 로그 키트에 출력됩니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 1월 23일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 아이템 키트에 음식의 이름이 표시되지 않던 오류를 수정했습니다.</p>
				<p>- 피드백 추천 기능이 작동하지 않던 오류를 수정했습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 1월 21일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 차량 파괴 횟수가 정확히 나오지 않던 오류를 수정했습니다.</p>
				<p>- 피드백 기능이 구조적으로 개선됐습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 1월 20일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- Admin 탭에 차량 파괴 로그 조회 키트가 추가되었습니다.</p>
				<p>- 차량 키트에 소유 차량 중 일부만 출력되던 오류를 수정했습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2016년 1월 17일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- (관리자의 경우)닉네임 대신 관리이름을 입력하여 로그인 할 수 있습니다.</p>
				<p>- 아이템의 상세 데이터가 인게임과 다르게 출력되던 오류를 수정했습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 12월 27일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 이제 밴 유저는 인포웹을 이용할 수 없습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 12월 26일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 아이템 키트에 UI가 적용되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 12월 22일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 이제 차량이 주차장에 있을 경우 주차장 이름이 표시됩니다.</p>
				<p>- Profile 탭에 아이템 키트가 추가되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 12월 18일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 모바일 지원 Scale Fix</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 12월 14일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- Faction 탭이 개설되었습니다.</p>
				<p>- 팩션을 요약 소개하는 키트가 추가되었습니다. 여기에는 팩션 로고, 명칭, 유형, 설립일, 리더, 부리더가 출력됩니다.</p>
				<p>- 정부군 팩션 기능 MDC 키트가 추가되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 12월 13일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 계정을 조회할 때 Field:Value 형식으로 조회할 수 있습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 12월 12일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- Admin 탭에 접속 로그 조회, 개명 로그 조회, 계정 조회 키트가 추가되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 12월 8일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- World탭에 각 플레이어의 Ping이 출력됩니다.</p>
				<p>- 차량 키트에 차량의 번호판 번호와 시동 여부가 출력됩니다.</p>
				<p>- 차량의 잠금여부가 실제와 다르게 나오던 오류를 해결했습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 12월 5일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 서버 상태 출력 알고리즘 개선</p>
				<p>- 게임에 접속되어 있어도 인게임 데이터 저장 안내문이 표시되지 않던 오류를 해결했습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 8월 13일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 차량 키트에 파괴, 차꺼냄 상태가 출력됩니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 8월 10일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 프로필 키트 하단의 시간이 프로필 정보 동기화 시각에서 고정되어 있도록 하였습니다.</p>
				<p>- 프로필 키트, 차량 키트의 출력 내용이 변경되었습니다.</p>
				<p>- 견인된 차량은 위치가 <u>견인됨</u>으로 출력됩니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 8월 8일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 팩션, 직업 이름 수정</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 8월 7일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 위치 표시에 <span style="color: #AA0000; font-weight: bold;">주차장</span> 추가</p>
				<p>- 게임에 접속중일 시 Profile 탭에 저장 안내 키트가 보여집니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 8월 6일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- Admin 탭에 유저 로그 조회, 로그인 세션 변경 키트 추가</p>
				<p>- 서버 상태가 제대로 표시되지 않던 오류 수정</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 7월 27일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- Admin 탭에 오프라인 프로세스 키트가 추가되었습니다. 이 키트에서는 경고, 차감, 칭찬, 돈지급, 돈회수가 가능합니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 7월 26일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 공용주차장에 있는 차량의 위치가 정확히 표시되지 않던 오류를 해결하였습니다.</p>
				<p>- Admin 탭의 밴 조회 키트의 리스트에 아직 차단 기간이 만료되지 않았거나 최근 한달 이내에 차단된 유저만 표시되도록 하였습니다. 대상을 지정하여 직접 조회할 때에는 기간에 상관없이 전체 DB에서 조회됩니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 2월 22일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- Admin 탭에 비밀번호 변경 키트가 추가되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 2월 21일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- ID밴 · IP밴 조회 시 LIKE 구문(MySQL)을 사용하도록 했습니다.</p>
				<p>- Safari 브라우저에서 로그인 키트의 비밀번호 라벨이 위치를 벗어나 있던 오류를 수정했습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 2월 20일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- ID밴 · IP밴 조회/해제 키트를 포함한 Admin 탭이 추가되었습니다.</p>
				<p>- Admin 탭에 ID밴 · IP밴 추가 키트가 추가되었습니다.</p>
				<p>- <img src="./favicon.png" style="width: 16px; height: 16px;" />&nbsp;파비콘이 적용되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 2월 17일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- Feedback 탭이 추가되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 2월 8일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 인터페이스 스케일 픽스를 해제했습니다.</p>
				<p>- Header, Footer 키트의 모양을 사각형으로 변경했습니다.</p>
				<p>- 영어를 조금 써 봤어요!</p>
				<p>- 이제 프로필 키트에도 현재 시각이 나옵니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2015년 1월 12일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 서버 상태가 옳바르게 표시되지 않던 오류를 수정하였습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 9월 20일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- (잘 모르시겠지만) 내부 구조가 매우 바뀌었습니다.</p>
				<p>- 페이지 로드 속도 엄청 빨라지지 않았나요?</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 9월 13일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 배경 이미지가 변경되었습니다.</p>
				<p>- 모바일에서 배경 이미지가 뭉게뭉게 뭉개지던 문제를 해결하였습니다.</p>
				<p>- 콘텐츠 레이아웃 좌우하단으로 그림자가 적용되었습니다.</p>
				<p>- 데이터 연동 방식을 변경하여 페이지 로드 속도가 매우 개선되었습니다. (Acu♡)</p> 
				<p>- 로그인을 3회 실패해도 차단되지 않던 오류를 해결하였습니다.</p>
				<p>- 서버 상태 키트의 배경 사진이 현재 시각과 맞지 않던 오류를 해결하였습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 9월 12일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 로그인 키트가 살짝 바뀌었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 9월 10일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 서버 상태 키트에 게임 서버 IP주소가 표시됩니다.</p>
				<p>- 월드 탭에 게임 시작 링크를 추가했습니다.</p>
				<p>- 자격증 키트가 리디자인되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 9월 2일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 좋은 건의 감사합니다! 이제 상단 네이브바에 로그인한 이용자의 게임 접속 여부가 표시됩니다. <span style="color: #55FF55">●</span>, <span style="color: #FF5555">●</span>, 게임 서버에 이상이 생겨 접속 여부를 알 수 없을 때에는 표시되지 않습니다.</p>
				<p>- 계정 조회 시스템의 명칭이 <b style="color: #0000AA">인포웹</b>으로 변경됩니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 9월 1일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 날씨 키트를 완성할 때까지 비활성화하겠습니다.</p>
				<p>- 월드 탭에 접속중인 이용자 명단 키트가 추가되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 8월 31일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 월드 탭이 리디자인되었습니다.</p>
				<p>- 자격증 키트가 리디자인되었습니다.</p>
				<p>- 스크롤을 움직여도 배경 이미지가 고정되어 있도록 하였습니다.</p>
				<p>- 이제 게임 서버의 IP가 변경되어도 자동으로 동기화합니다.</p>
				<p>- 월드 탭이 로딩 중일 때 게임 서버가 점검중이라고 표시되는 버그를 수정했습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 8월 29일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 배경 이미지를 적용하였습니다.</p>
				<p>- 이제 로그인 시 언더바(_), 대소문자를 구분하지 않아도 됩니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 8월 22일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 서버 상태 키트가 추가되었습니다.</p>
				<p>- 키트 별로 색상을 다르게 하였습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 8월 20일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 로그인 키트의 인터페이스가 변경되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 8월 17일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 차량 키트가 추가되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 8월 16일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 키트의 제목와 내용이 구분되도록 하였습니다.</p>
				<p>- 이제 로그인을 해야 계정을 조회할 수 있습니다.</p>
				<p>- 계정 조회 시스템이 오픈되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 8월 15일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 프로필 키트에 캐릭터의 스테이터스가 출력됩니다.</p>
				<p>- 자격증 키트가 추가되었습니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 8월 12일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 프로필 키트에 캐릭터 스킨의 이미지가 표시됩니다.</p>
			</div>

			<div id="conbox" class="head">
				<h1>2014년 8월 11일</h1>
			</div>
			<div id="conbox" class="body">
				<p>- 계정 조회 시스템이 테스트 오픈되었습니다.</p>
				<p>- 페이지 상단 네이브바에 닉네임과 캐릭터의 국적이 표시됩니다.</p>
			</div>
			<?php
include "./footer.inc.php";
			?>
		</div>
	</body>
</html>