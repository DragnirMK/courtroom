/*
	* Creation des variables globales idRoom et numberOfEntries
	* On initialise idRoom à 0 (correspond à la première salle de chat)
	* On initialise numberOfEntries à -1
*/

var idRoom = 0;
var numberOfEntries = -1;

/*
	* Lors du chargement de la page, on récupère toutes les salles, les messages de la première
	* salle de chat et on initialise un timer qui nous permet d'actualiser le chat  
*/

$(document).ready(function() {

	getChatRooms();
	getChatLog(0);

	switch(idLogin) {
		case 0:
			$("#header-bar").css('background-color', 'rgb(66, 135, 245)');
			break;
		case 1:
			$("#header-bar").css('background-color', 'rgb(140, 17, 45)');
			break;
		case 2:
			$("#header-bar").css('background-color', 'rgb(192, 192, 192)');
			break;
		case 3:
			$("#header-bar").css('background-color', 'rgb(128, 136, 104)');
			break;
		case 4:
			$("#header-bar").css('background-color', 'rgb(181, 55, 56)');
			break;
		case 5:
			$("#header-bar").css('background-color', 'rgb(226, 181, 35)');
			break;
		default:
			$("#header-bar").css('background-color', 'rgb(255, 255, 255)');
			break;
	}

	window.setInterval(function() {
		verifyNewEntries();
	}, 2000);
});

/*
	* Fait appel à la fonction "sendMessage(idRoom)" lorsque le formulaire est envoyé
*/

$("#chat-form").on('submit', function(e) {

	var content = $("#chat-content-input");

	if (content.val().length != 0) {
		sendMessage(idRoom);
		content.val("");
	}
	e.preventDefault();
});

/*
	* Fait appel à la fonction getChatLog(idRoom) lorsqu'on clique sur une salle de chat
*/

$(document).on('click', '.room', function(e) {

	idRoom = $(this).attr('id').split("-")[1];
	getChatLog(idRoom);
	$('button').removeClass('selected');
	$(this).addClass('selected');
});

/*
	* fonction sendMessage(idRoom)
	* fait appel à "enregistrerMessage.php"
	* permet d'envoyer un message dans la base de données
*/

function sendMessage(idRoom) {

	console.log(idLogin);
    $.get({
    	url: "enregistrerMessage.php",
    	data: {
    		login: idLogin,
    		content: $("#chat-content-input").val(),
    		room: idRoom
    	}
    });
}

/*
	* fonction verifyNewEntries()
	* fait appel a "verifierNouveauxMessages.php"
	* compare la variable globale "numberOfEntries" et le resultat
	* si différent, alors on actualise le chat et la valeur de "numberOfEntries" est mise à jour
	* sinon, on ne fait rien
*/

function verifyNewEntries() {

	$.post({
		url: "verifierNouveauxMessages.php",
		data: null,
		success: function(data) {
			if (numberOfEntries != data[0]) {
				numberOfEntries = data[0];
				getChatLog(idRoom);
			}
		},
		dataType: "json"
	});
}

/*
	* fonction getChatLog(idRoom)
	* fait appel a "recupererChat.php"
	* recupère les 10 derniers messages de la salle de chat idRoom
	* on traite le resultat et on l'affiche
*/

function getChatLog(idRoom) {

	$.post({
		url: "recupererChat.php",
		data: {
			room: idRoom,
			login: idLogin
		},
		success: function(data) {
			$("#chat-log").empty();
			for (var i = 0; i < data.length; i++) {
				var message = document.createElement('div');
				message.classList.add('chat-message');

				var userDiv = document.createElement('div');
				var profilePicture = document.createElement('img');
	            var author = document.createElement('h4');
	            var content = document.createElement('p');
	            var timeStamp = document.createElement('h5');

	            userDiv.id = "user-info";
	            profilePicture.setAttribute('src', data[i].path);
				author.innerHTML = data[i].user;
	            content.innerHTML = data[i].content;

	            var currentDate = new Date();
	            var messageDate = new Date(data[i].timeSend);
	            var timeInBetween = getTimeStamp(currentDate, messageDate);
	            timeStamp.innerHTML = timeInBetween;

	            userDiv.appendChild(profilePicture);
	            userDiv.appendChild(author)
	            userDiv.appendChild(timeStamp);
	            message.appendChild(userDiv)
	            message.appendChild(content);
	            $("#chat-log").append(message);
			}
		},
		dataType: "json"
	});
}

/*
	* fonction getChatRooms()
	* fait appel a "recupererSalles.php"
	* on traite le resultat et on l'affiche
*/

function getChatRooms() {

	$.post({
		url: "recupererSalles.php",
		data: null,
		success: function(data) {
			for (var i = 0; i < data.length; i++) {
				var chatRoom = document.createElement('button');
				chatRoom.classList.add('room');
				chatRoom.id = "room-" + data[i].idRoom;
				chatRoom.name = data[i].name;
				chatRoom.innerHTML = "# " + data[i].name;
				$("#room-nav").append(chatRoom);
			}
			$('#room-0').addClass('selected');
		},
		dataType: "json"
	});

}

/*
	* fonction getTimeStamp(date1, date2)
	* date1 correspond au timestamp actuel
	* date2 correspond au timestamp du message
	* permet de gérer l'affichage du timestamp dans le chat
*/

function getTimeStamp(date1, date2) {

	var timeStamp;
	var difference = date1 - date2;

	var nbHours = Math.floor(difference/1000/60/60);

	var formatHours = (date2.getHours() % 12 == 0) ? (12) : (date2.getHours() % 12);
	var formatMinutes = addZero(date2.getMinutes());
	var formatMonth = ((date2.getMonth() + 1) < 10) ? "0" + (date2.getMonth() + 1) : date2.getMonth() + 1;
	var amOrPm = (date2.getHours() < 12) ? "AM" : "PM";

	if (nbHours < 24) {
		timeStamp = "Today at " + formatHours + ":" + formatMinutes + " " + amOrPm;
	} else if (nbHours > 24 && nbHours < 48) {
		timeStamp = "Yesterday at " + formatHours + ":" + formatMinutes + " " + amOrPm;
	} else {
		timeStamp = getWeekDay(date2) + " " + date2.getDate() + "/" + formatMonth + " at " + formatHours + ":" + formatMinutes + " " + amOrPm;
	}
	return timeStamp;
}

/*
	* fonction getWeekDay(date)
	* renvoit le jour de la semaine correspondant
	* en fonction du resultat de la fonction getDay()
*/

function getWeekDay(date) {

	var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	return weekday[date.getDay()];
}

/*
	* fonction addZero(i)
	* permet de gérer l'affichage des minutes
*/

function addZero(i) {

  if (i < 10) {
    i = "0" + i;
  }
  return i;
}
