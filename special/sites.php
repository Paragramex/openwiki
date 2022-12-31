<?php

?>


<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: white; display: table;">
<div style="display: table-row; height: 0px;"><div style="text-align: center;display: table-cell; height: 0px; background-color: lightgray; padding: 7px;" id="time">
</div></div>
<div style="display: table-row; height: 100%;">
<div id="windows" style="display: table-cell; height: 100%;">
<div id="notabs" style="height: 100%; width: 100%; background-image: url(https://alanhw.weeklyd3.repl.co/$BACKGROUND);"></div>
</div>
</div>
<div style="display: table-row; height: 0px;"><div id="tabs" style="display: table-cell; height: 0px; background-color: lightgray;">
<label>
<span style="position: fixed; top: -9999px;">New:</span>
<select id="minigames" onchange="newGame(this.options[this.selectedIndex].text); this.selectedIndex = 0;">
<option disabled="disabled" selected="selected" style="display: none;">Open...</option>
<option disabled="disabled">Choose an application to open</option>
</select>
</label>
<span id="opengames"></span>
</div>
</div>
</div>
<style>
.close-button { color: white; background-color: black; margin: 3px; } .gametab { display: inline-block; background-color: white; color: black; padding: 7px; border: 1px solid; border-radius: 3px; margin: 3px;} .active { background-color: cyan; color: black; }
</style>
<script>
globalThis.miFnigames =[
	{
		"name": "Change background image",
		"url": "https://openwiki.paragram.repl.co/index.php?title=Special:sitesPageChooseNewBackground"
	},
              "Harmlesswebsite",
              {
              "name": "Harmlesswebsite maze game",
              "url": "https://hw-maze-game.weeklyd3.repl.co"
          },
              {
                                     "name": "HarmlessWebsite",
                                     "url" :"https://harmlesswebsite.leoshi6.repl.co"
                                 },
              {
                                     "name": "Endless Enemies",
                                     "url" :"https://harmlesswebsite.leoshi6.repl.co/endlessEnemies.html"
                                 },
              {
                                     "name": "Stone Age Survival",
                                     "url" :"https://harmlesswebsite.leoshi6.repl.co/stoneAgeSurvival.html"
                                 },
                                 {
                                     "name": "BOORING",
                                     "url" :"https://harmlesswebsite.leoshi6.repl.co/boring.html"
                                 },
                                 {
                                     "name": "Harmlesswebsite clicker",
                                     "url": "https://harmlesswebsite.leoshi6.repl.co/clicker.html"
                                 },
                                 {
                                     "name": "Calculator",
                                     "url": "https://harmlesswebsite.leoshi6.repl.co/calculator.html"
                                 },
              {
                  "url": "https://harmlesswebsite.leoshi6.repl.co/gameOfLifeSimulator.html",
                  "name": "Game of life Simulator"
              },
                                 {
                                     "name": "Harmlesswebsite embedded",
                                     "url": "https://harmlesswebsite.leoshi6.repl.co"
                                 },
              {
                  "name": "Embedded world map",
                  "url": "https://harmlesswebsite.leoshi6.repl.co/map.html"
              },
              {
                "name": "Typing test",
                "url": "https://harmlesswebsite.leoshi6.repl.co/typingTest.html"
              },
              "AlanHw and FeewayStoryMode",
              {
                  "url": "https://alanhw.weeklyd3.repl.co",
                  "name": "ALANHW"
              },
              {
                  "url": "https://feewaystorymode.alansfartfwy.repl.co",
                  "name": "FeewayStorymode"
              },
              {
                  "url": "https://alanhw.weeklyd3.repl.co/alanhw133.html",
                  "name": "Really interesting"
              } /*,
			  If you don't mind imma comment
	 		this out because it is accessible
	from the rest of story mode y is the lapotty background the original one and not the photoshopped one can u fix it
              {
                  "url": "https://feewaystorymode.alansfartfwy.repl.co/minigame.html",
                  "name": "The interstate system (feeway minigame)"
              } */,
              {
                  "url": "https://alanhw.weeklyd3.repl.co/terminal.html",
                  "name": "Terminal"
              },
              {
                  "url": "https://alanhw.weeklyd3.repl.co/never_gonna_give_u_up_parody.html.html",
                  "name": "Parody of the rickroll"
              },
              {
                  "url": "https://alanhw.weeklyd3.repl.co/weird-traffic-lights.html",
                  "name": "Hey I see some Weird Traffic Lights"
              },
              {
                  "url": "https://alanhw.weeklyd3.repl.co/zondomongo.html",
                  "name": "Zondo mongo goes ka-bongo"
              },
              {
                  "url": "https://alanhw.weeklyd3.repl.co/schoology.html",
                  "name": "Hey, we have homework"
              },
              "Utilities",
              {
                  "url": "https://harmlesswebsite.leoshi6.repl.co/ide.html",
                  "name": "Quick HTML test"
              },
              {
                  "url": "https://openwiki.paragram.repl.co",
                  "name": "OpenWiki/this wiki"
              }
                                ];
          var randomBackground;
          var backgrounds = [
              'better_bird_poop_road.png',
              'lapotty_indiana.png',
              'rickastley.png',
              'BSIV.png'
          ];
          randomBackground = backgrounds[Math.floor(Math.random()*backgrounds.length)];
	if (localStorage.getItem('custom-background') && localStorage.getItem('custom-background') !== 'none') randomBackground = localStorage.getItem('custom-background');
		document.querySelector('#notabs').style.cssText = document.querySelector('#notabs').style.cssText.replace('$BACKGROUND', randomBackground);

          document.getElementById('time').textContent = getDateString();
          setInterval(function() { document.getElementById('time').textContent = getDateString(); }, 60000);
          for (var i = 0; i < minigames.length; i++) {
              if (minigames[i] instanceof Object) {
              var game = document.createElement('option');
              game.textContent = minigames[i].name;
              document.getElementById('minigames').appendChild(game);
              } else {
              var group = document.createElement('optgroup');
              group.label = minigames[i];
              document.getElementById('minigames').appendChild(group);
          }
          }
	function newGame(game) {
    for (var i = 0; i < minigames.length; i++) {
        if (minigames[i].name === game) {
            newTab(game, minigames[i].url);
        }
    }
}
function newTab(name, url) {
    var tab = document.createElement('span');
    var currentTabs = document.querySelectorAll('.gametab');
    for (var i = 0; i < currentTabs.length; i++) currentTabs[i].classList.remove('active');
    for (var i = 0; i < document.querySelectorAll('#windows > div').length; i++) document.querySelectorAll('#windows > div')[i].style.display = 'none';
    tab.classList.add('active');
    tab.classList.add('gametab');
    tab.tabIndex = 0;
    tab.setAttribute('id', "tab" + (currentTabs.length + 1));
    tab.addEventListener('click', function(ev) {
        openTab(this.getAttribute('id').slice(3), ev);
    });
    var closeButton = document.createElement('span');
    closeButton.textContent = '×';
    closeButton.addEventListener('click', function(ev) {
        console.log('Closing tab.');
        this.parentNode.style.display = 'none';
        console.log('Done closing');
        for (var i = 0; i < document.querySelectorAll('#windows > div').length; i++) document.querySelectorAll('#windows > div')[i].style.display = 'none';
        document.getElementById(`window${this.parentNode.getAttribute('id').slice(3)}`).parentNode.removeChild(document.getElementById(`window${this.parentNode.getAttribute('id').slice(3)}`));
        document.getElementById('notabs').style.display = 'block';
        ev.stopImmediatePropagation();
        ev.stopPropagation();
    });
    closeButton.classList.add('close-button');
    tab.textContent = (currentTabs.length + 1) + ". " + name;
    tab.style.cursor = 'pointer';
    tab.appendChild(closeButton);
    document.getElementById('opengames').appendChild(tab);
    var window = document.createElement('div');
    window.setAttribute('id', `window${currentTabs.length + 1}`);
    window.style.height = '100%';
    window.style.display = 'none';
    var i = document.createElement('iframe');
    i.src = url;
    i.style.display = 'block';
    i.style.height = '100%';
    i.style.width = '100%';
    i.style.border = 'none';
    window.appendChild(i);
    document.getElementById('windows').appendChild(window);
    openTab(currentTabs.length + 1, {"stopImmediatePropagation": function(){return;}});
}
function openTab(id, ev) {
    ev.stopImmediatePropagation();
    if (!document.querySelector(`#tab${id}`)) throw new DOMException('Invalid ID passed');
    for (var i = 0; i < document.querySelectorAll('#windows > div').length; i++) document.querySelectorAll('#windows > div')[i].style.display = 'none';
    var currentTabs = document.querySelectorAll('.gametab');
    for (var i = 0; i < currentTabs.length; i++) currentTabs[i].classList.remove('active');    
    document.querySelector(`#tab${id}`).classList.add('active');
    document.querySelector(`#window${id}`).style.display = 'block';
}
function getDateString() {
    var d = new Date();
    var days = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
    ];
    var months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
    ]
    var day = days[d.getDay()].slice(0, 3);
    var month = months[d.getMonth()].slice(0, 3);
    return `${day} ${month} ${d.getDate()} ${d.getHours()}:${(d.getMinutes() + "").padStart(2, '0')}`;
}
		  </script>