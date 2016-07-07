numCorrect = 0;
var rndm;

var questions = 
		[
			{question: "T/F: Water bottles are recyclable.", answer: "True"}, 
			{question: "Which of these can be composted?", answer: "Banana Peel"},
			{question: "Which material is most harmful to the enviroment?", answer: "Plastic"},
			{question: "Recycling aluminum cans helps to reduce the amount of ____ wasted:", answer: "Oil"}, 
			{question: "How much waste does the US produce each year on average?", answer: "254 million lbs"}, 
			{question: "Where should you dispose hazardous waste?", answer: "Waste management facilities"}, 
			{question: "What is recycling's greatest benefit?", answer: "Resource efficiency"},
			{question: "What do aerosol spray cans do to the environment?", answer: "Deplete the ozone layer"},
			{question: "What can you do to reduce waste?", answer: "Reduce, reuse, recycle"}
		];
questions2 = questions;

var answers =
		[
			["True", "False"],
			["Plastic", "Glass", "Batteries", "Banana Peel", "Truffle Butter"],
			["Glass", "Plastic", "Cardboard", "Aluminum", "Isis"],
			["Sizzurp", "Soda", "Drank", "Oil"],
			["254 million lbs", "1738 million lbs", "420 million lbs", "69 million lbs"],
			["In the trap house", "Toilet", "At the bando", "Waste management facilities"],
			["It's lit", "Resource efficiency", "Lowers taxes", "It looks good on your resume"],
			["Make your hair look good", "Cover the smell in the bathroom", "Deplete the ozone layer", "Make it rain"],
			["Peep my mixtape", "Support me on soundcloud", "Reduce, reuse, recycle", "Like and subscribe to my Youtube channel"]
		];
answers2 = answers;

function hideButtons() { 
	document.getElementById("submitButton").style.display="none";
	document.getElementById("exitButton").style.display="none";
}


function randomQuestion() {
	rndm = Math.floor(Math.random() * questions2.length);
	document.getElementById("question").innerHTML = questions2[rndm].question;
}

function showAnswers() {
	if (questions2.length === 0) {
    	location.reload();
    }
	document.getElementById("startButton").style.display = "none";
	document.getElementById("score").innerHTML = "Score is: "+numCorrect;
	document.getElementById("submitButton").style.display = "inline";
	document.getElementById("exitButton").style.display = "inline";
	var options = document.getElementsByClassName("ch");
	if (questions2.length > 0) {
		randomQuestion();
		var answerDiv = document.getElementById("answers");
		var ulist = document.createElement("ul");
		ulist.className = 'choices';
		ulist.setAttribute('id','answerOptions');
		for (var i = 0; i < answers2[rndm].length; ++i) {
			var choice = document.createElement("input");
			choice.className = 'ch';
			choice.setAttribute('type','radio');
			choice.setAttribute('id','id'+i);
			choice.setAttribute('name','answer');
			choice.setAttribute('value', answers2[rndm][i]);
			ulist.appendChild(choice);
			var answerLabel = document.createElement("label");  
			answerLabel.htmlFor = choice.id;  
			answerLabel.appendChild(choice); 
			var answerNameTextNode = document.createTextNode(answers2[rndm][i]);
			answerLabel.appendChild(answerNameTextNode);  
			ulist.appendChild(answerLabel);
			var brElement = document.createElement("br");
			ulist.appendChild(brElement);
		}
	}
		answerDiv.appendChild(ulist);
}

function checkAnswer() {
	var answered = document.getElementsByClassName("ch");
	var selected = false;
	if (questions2.length > 0) {
		for (var j = 0; j < answered.length; j++) {
			if (answered[j].checked == true && answered[j].value == questions2[rndm].answer) {
				selected = true;
				numCorrect++;
				document.getElementById("correctOrNah").innerHTML = "Correct!";
				document.getElementById("score").innerHTML= "Score is: "+numCorrect;
				clearAnswers();
				questions2.splice(rndm, 1);
				answers2.splice(rndm, 1);
				
			}	
			else if (answered[j].checked == true && answered[j].value != questions2[rndm].answer) {
				selected = true;
				document.getElementById("correctOrNah").innerHTML = "Nope!";
				document.getElementById("score").innerHTML= "Score is: "+numCorrect;
				clearAnswers();
				questions2.splice(rndm, 1);
				answers2.splice(rndm, 1);
			}
		}
		if (questions2.length > 0) {
				randomQuestion();
				showAnswers();
			}
		if (questions2.length == 0)	{
			document.getElementById("startButton").style.display = "inline";
			document.getElementById("question").innerHTML = "";	
			hideButtons();
			document.getElementById("correctOrNah").innerHTML = "You have completed the quiz!";
		}
		if (!selected) {
			clearAnswers();
			document.getElementById("correctOrNah").innerHTML = "Nope!";
			alert("Please select an answer");
		}
	}		
}

function clearAnswers() {
	var clearOptions = document.getElementById("answers");
	clearOptions.removeChild(document.getElementById("answerOptions"));
}

function exitQuiz() {
	location.reload();
}




