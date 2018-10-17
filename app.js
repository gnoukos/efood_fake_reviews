var jsonResponse;
var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                jsonResponse = JSON.parse(this.responseText);
                getJSON(jsonResponse);
            }
        };
        xhttp.open("POST", "calculate.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("shopurl="+shop);
        
function getJSON(jsonResponse){
  if(jsonResponse.result == true) {
    document.querySelector('.card').style.opacity = '1';
    
    document.querySelector('.loader').style.display = 'none';

    document.querySelector(".card__image").src = jsonResponse.logo;

    document.querySelector(".card__title").innerHTML = jsonResponse.title;  

    progressBar(jsonResponse.score);

    var listInners = document.querySelectorAll('.stars__inner');
    var listRatings = document.querySelectorAll('.stars__rating');
    
    listInners[0].style.width = `${ (jsonResponse.stars / 5) * 100}%`;
    listRatings[0].textContent = jsonResponse.stars;
    
    listInners[1].style.width = `${ (jsonResponse.realStars / 5) * 100}%`;
    listRatings[1].textContent = jsonResponse.realStars;

  }else{
      document.querySelector('.card').style.display = 'none';
      document.querySelector('.loader').style.display = 'none';

      var body = document.querySelector('body');
      var card = document.querySelector('.card');
      var p = document.createElement('p');
      var text = document.createTextNode('Μη έγκυρο URL !');
      p.className = 'error__message';
      p.appendChild(text);
      body.insertBefore(p, card);
  }
}


function progressBar(score){
  var bar = new ProgressBar.Circle(container, {
    color: '#aaa',
    strokeWidth: 1,
    trailWidth: 3,
    easing: 'easeInOut',
    duration: 2000,
    text: {
      autoStyleContainer: false
    },
    from: { color: '#383A3F', width: 4 },
    to: { color: '#333', width: 4 },
    step: function(state, circle) {
      circle.path.setAttribute('stroke', state.color);
      circle.path.setAttribute('stroke-width', state.width);
      
      circle.setText(score + '%');
    }
  });
  bar.text.style.fontFamily = '"Noto Sans", sans-serif';
  bar.text.style.fontSize = '2rem';
  bar.text.style.color = '#fff';
  bar.text.style.fontWeight = '600';
  
  bar.animate(score/100);
}