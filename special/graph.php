<?php
/*
This file is part of paragrams OpenWiki system, find him here: github.com/Paragramex or on replit: replit.com/@paragram.
*/
 $title = "Graph equations"; ?>
<div id="pad"></div><script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.4.2/p5.js" integrity="sha512-+tu0+vUXyZX+S51npa//IN6znNTLZsBoy8mXn9WzHxfBqYMy6gOzzfTK0VqZf9O171RY9AJviHDokCnvEq8+1A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.0.1/math.js" integrity="sha512-qUD6aWQY0c9uVWnPVcbUzQg9Q06qfCpZhOK7jbWDKEAuX+i6gwS5P2VoDe+ZghmUepiB1FtBY5gNosseexrt9Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>
	.equation-error { color: red; font-weight: 700; }
</style>
<script>
	const increment = 0.02;
	const h = 400;
	const w = 600;
	var tl = [-30, 20];
	var br = [30, -20];
	var zoom = 10;
var s = function(sketch) {
    sketch.setup = function() {
        sketch.createCanvas(w, h);
        updateDrawing();
    };
};
var spawnsPerX = {};
var draw = new p5(s, 'pad');

function updateDrawing() {
	draw.clear();
	draw.noStroke();
	drawHorizontalCoordinateLines();
	drawVerticalCoordinateLines();
    if (tl[0] < 0 && 0 < br[0]) drawYAxis();
	if (tl[1] > 0 && 0 > br[1]) drawXAxis();

	addEquations();
	saveToHash();
}
function drawHorizontalCoordinateLines() {
	draw.fill('#999999');
	for (var i = 0; i <= h / zoom; i++) {
		draw.rect(0, i * zoom, w, 1);
	}
}
function drawVerticalCoordinateLines() {
	for (var i = 0; i <= w / zoom; i++) {
		draw.fill('#999999');
		draw.rect(i * zoom, 0, 1, h);
	}
}
function drawYAxis() {
	draw.fill('#000000');
	draw.rect(-tl[0] * zoom, 0, 2, h);
}
function drawXAxis() {
	draw.fill('#000000');
	draw.rect(0, h - (-br[1] * zoom), w, 2);
}
	function moveAbs(x, y) {
		tl[0] += x;
		tl[1] += y;
		br[0] += x;
		br[1] += y;
		updateDrawing();
	}
	function addEquation() {
		const template = document.querySelector('#equation-template li').cloneNode(true);
		template.querySelector('select').addEventListener('change', updateDrawing);
		template.querySelector('input[type=text]').addEventListener('change', updateDrawing);
		template.querySelector('input[type=color]').addEventListener('change', updateDrawing);
		template.querySelector('.rm-equation').addEventListener('click', function() {
			template.parentNode.removeChild(template);
			updateDrawing();
		})

		document.querySelector('#equations').appendChild(template);
		return template;
	}
	function addEquations() {
		const equations = document.querySelector('#equations').children;
		Array.from(equations).forEach(function(equation) {
			equation.querySelector('.equation-error').textContent = '';
			draw.fill(equation.querySelector('input').value);
			const mode = equation.querySelector('select').value;
			var pointToPlot = [];
			switch (mode) {
				case 'y':
					var x = tl[0];
					while (x <= br[0]) {
						try {
							const result = math.evaluate(equation.querySelector('[type=text]').value, {x: x});
							if (typeof result === 'object') {
								x += increment;
								continue;
							}
							if (result === Infinity) throw new Error("Expression returned Infinity. There must be something wrong.");
							if (result === undefined) throw new Error("Expression is empty or it returns nothing.");
							pointToPlot = [x, result];
						} catch (e) {
							equation.querySelector('.equation-error').textContent = e.toString();
							x += increment;
							continue;
						}
						x += increment;
						const xcoord = (-tl[0] + pointToPlot[0]) * zoom;
						const ycoord = h - (-br[1] + pointToPlot[1]) * zoom;
						draw.rect(xcoord, ycoord, 2, 2);
					}
					break;
				case 'x':
					// vary y
					var y = br[1];
					while (y <= tl[1]) {
						try {
							const result = math.evaluate(equation.querySelector('[type=text]').value, {y: y});
							if (typeof result === 'object') {
								y += increment;
								continue;
							}
							if (result === Infinity) throw new Error("Expression returned Infinity. There must be something wrong.");
							if (result === undefined) throw new Error("Expression is empty or it returns nothing.");
							pointToPlot = [result, y];
						} catch (e) {
							equation.querySelector('.equation-error').textContent = e.toString();
							y += increment;
							continue;
						}
						y += increment;
						const x = (-tl[0] + pointToPlot[0]) * zoom;
						const ycoord = h - (-br[1] + pointToPlot[1]) * zoom;
						draw.rect(x, ycoord, 2, 2);
					}
					break;
			} 
		});
	}
function loadFromHash() {
	if (window.noLoad) return window.noLoad = false;
	if (!location.hash.startsWith(`#graph=`)) return;
	const graphData = JSON.parse(atob(location.hash.slice(`#graph=`.length)));
		document.querySelector('#equations').innerHTML = '';
	graphData.forEach(function(data) {
		const node = addEquation();
		node.querySelector('input').value = data.color;
		node.querySelector('select').selectedIndex = ['x', 'y'].indexOf(data.direction);
		node.querySelector('input[type=text]').value = data.equation;
	})
}
	window.addEventListener('hashchange', loadFromHash);

	addEventListener('DOMContentLoaded', loadFromHash);
function saveToHash() {
	const equations = document.querySelector('ul#equations').children;
	const data = [];
	Array.from(equations).forEach(function(equation) {
		const item = {};
		item.direction = equation.querySelector('select').value;
		item.equation = equation.querySelector('[type=text]').value;
		item.color = equation.querySelector('input').value;
		data.push(item);
	})
	window.noLoad = true;
	location.hash = `graph=${btoa(JSON.stringify(data))}`;
}
</script>
<div>
	Move:
	<div style="display: table;">
		<div style="display: table-row;">
			<div style="display: table-cell;"></div>
			<div style="display: table-cell;"><button onclick="moveAbs(0, 1);">▲</button></div>
			<div style="display: table-cell;"></div>
		</div>
		<div style="display: table-row;">
			<div style="display: table-cell;"><button onclick="moveAbs(-1, 0);">◀</button></div>
				<div style="display: table-cell;"></div>
				<div style="display: table-cell;"><button onclick="moveAbs(1, 0);">▶</button></div>
		</div>
		<div style="display: table-row;">
			<div style="display: table-cell;"></div>
			<div style="display: table-cell;"><button onclick="moveAbs(0, -1);">▼</button></div>
			<div style="display: table-cell;"></div>
		</div>
	</div>
</div>
<ul id="equations"></ul>
<button onclick="addEquation()">Add equation</button>
<div id="equation-template" hidden="hidden">
	<li>
		<label>
			<span class="hidden2eyes">Color of line on graph:</span>
			<input type="color" value="#ff0000" />
		</label>
		<label>
			<span class="hidden2eyes">Equation type:</span>
			<select class="equation-type">
				<option value="x">x =</option>
				<option value="y" selected="selected">y =</option>
			</select></label>
		<label>
			<span class="hidden2eyes">Equation:</span>
			<input class="equation" type="text" /></label>
		<button class="rm-equation">&times;</button>
		<span class="equation-error"></span>
	</li>
</div>