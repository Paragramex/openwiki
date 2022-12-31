// Put extra code that will
// run on page load here.
addEventListener('DOMContentLoaded', function() {
    const c = window.config;
    if (!c.loggedIn) return console.log('Not logged in');
    const userbuttons = [];
    const welcome = document.createElement('input');
    welcome.type = 'button';
    welcome.value = ':)';
    welcome.title = 'Welcome user';
    if (config.originalPageName.startsWith('User:')) var username = c['originalPageName'].slice(5);
    if (config.originalPageName.startsWith('User talk:')) var username = c['originalPageName'].slice('User talk:'.length);
    globalThis.username = username;
    welcome.addEventListener('click', function() {
        const dialog = addDialog(`Welcoming user ${username}`, `Checking for existence of talk page...`).body;
        globalThis.welcomeDialog = dialog;
        fetch(`api.php?action=parse&title=User+talk:${encodeURIComponent(username)}&json`)
        .then((res) => res.json())
        .then(function(json) {
            if (json.query[`User talk:${username}`].exists) globalThis.talkExists = true;
            else globalThis.talkExists = false;
            if (json.query[`User talk:${username}`].exists && !confirm('Talk page already exists. Welcome anyway?')) throw new Error('Aborted by user.');
        })
        .then(function() {
            dialog.textContent += `\nWelcoming user...`;
            dialog.textContent += `\nInitializing talk page...`;
            dialog.innerHTML += `<form action="javascript:;" onsubmit="submitWelcome(document.querySelector('#welcome').value, document.querySelector('#addheader').checked, ${talkExists});"><label>Welcome message (will have talk header prepended to it if you wish, PLEASE BE SURE TO REPLACE PAGENAME WITH PAGE NAME THE USER WAS EDITING):<br /><textarea id="welcome" rows="5" cols="50"></textarea></label><br /><label><input id="addheader" type="checkbox" /> Add talk page header</label><br /><input type="submit" value="welcome" /></form>`;
            document.querySelector('#welcome').textContent = `## Welcome!\nHi [[User:${username}|${username}]]! I noticed your contributions to [[PAGENAME]] and wanted to welcome you to the community. Some things you can do:\n * Ask for help at the [[Help desk]]\n * [[User:${username}|Create your user page with a bit of information about yourself]]\n\nAs mentioned above, if you need help, please ask at the [[Help desk]] or on [[User talk:${config.username}|my talk page]]. Thanks and happy editing! ~~~~\n`;
        })
        .catch(function(error) {
            dialog.textContent += `\nFATAL ERROR: ${error}`;
        });
    });
    userbuttons.push(welcome);
    const buttons = [];
    if (c['originalPageName'].startsWith('User:') || c['originalPageName'].startsWith('User talk:')) buttons.push(...userbuttons);

    buttons.forEach(function(button) {
        document.querySelector('#thispage').appendChild(button);
    })
});
function addDialog(title, text, closable = true) {
    const dialog = document.createElement('div');
    dialog.style.width = '75%';
    dialog.style.maxHeight = '75%';
    dialog.style.overflow = 'auto';
    dialog.style.position = 'fixed';
    dialog.style.top = '50%';
    dialog.style.left = '50%';
    dialog.style.transform = 'translate(-50%, -50%)';
    dialog.style.backgroundColor = 'skyblue';
    dialog.style.padding = '5px';

    const taitl = document.createElement('div');
    taitl.textContent = title;
    taitl.style.backgroundColor = 'blue';
    taitl.style.color = 'white';
    taitl.style.padding = '5px';
    taitl.appendChild(document.createTextNode("\u00A0"));
    const close = document.createElement('button');
    if (!closable) close.disabled = 'disabled';
    close.title = 'Close';
    close.setAttribute('aria-label', 'Close dialog');
    close.textContent = "×";
    close.style.padding = '1px 4px';
    close.style.boxShadow = 'none';
    close.style.verticalAlign = 'middle';
    close.style.float = 'right';
    const clear = document.createElement('div');
    clear.style.clear = 'both';
    taitl.appendChild(close);
    taitl.appendChild(clear);
    close.addEventListener('click', function() { this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); });
    dialog.appendChild(taitl);
    document.body.appendChild(dialog);
    const textbox = document.createElement('div');
    textbox.style.whiteSpace = 'pre-wrap';
    textbox.innerHTML = text;
    dialog.appendChild(textbox);
    return {
        dialog: dialog,
        title: taitl,
        body: textbox
    };
}
function submitWelcome(text, addHeader, talkExists) {
    if (addHeader) var welcomeText = `This is [[User:${username}|${username}]]'s talk page, where you can send this user messages.\n * Please assume <del>bad</del> <ins>good</ins> faith\n * No personal attacks\n * New messages go at the bottom\n * Open a new section using two hashtags\n * Sign your messages using four tildes\n\n${text}\n`; 
    else var welcomeText = text + "\n";
    welcomeDialog.querySelector('form').textContent = 'Enter welcome message - Complete!';
    welcomeDialog.textContent += "\nWelcoming user...";
        welcomeDialog.textContent += "\nTalk page exists. Getting talk page contents...";
        fetch(`api.php?action=parse&title=User+talk:${encodeURIComponent(username)}&json`)
        .then((res) => res.json())
        .then(function(json) {
            console.log(json);
            globalThis.talkPageContents = json.query[Object.keys(json.query)[0]].markdown;
            if (!talkPageContents) talkPageContents = "<!-- New messages go at the bottom. Thanks -->\n";
            console.log(talkPageContents);
            if (!globalThis.talkPageContents.endsWith("\n")) globalThis.talkPageContents += "\n";
        })
        .then(function() {
    welcomeDialog.textContent += "\nSaving talk page...";
    globalThis.talkPageContents = globalThis.talkPageContents + welcomeText;
    console.log(talkPageContents);
    const baudy = new FormData();
    baudy.set('contents', globalThis.talkPageContents);
    return fetch(`api.php?action=edit&json&title=User+talk:${encodeURIComponent(username)}&summary=Welcoming+user+${encodeURIComponent(username)}.+(using+the+Feeway+Wiki's+ModTools)`, {
        method: 'POST',
        body: baudy,
        credentials: 'include'
    });
        })
    .then((res) => res.json())
    .then(function(json) {
        if (json.errors && json.errors[0]) throw new Error(`API error: ${json.errors[0].type}: ${json.errors[0].reason}`);
        welcomeDialog.textContent += `\nUSER WELCOMED, navigating to talk page in З seconds.`;
        setTimeout(() => location.href = `index.php?title=User+talk:${encodeURIComponent(username)}`, 3000);
    })
    .catch(function(error) {
        welcomeDialog.textContent += `\nFATAL ERROR during API call: ${error.toString()}`;
    });
}

addEventListener('DOMContentLoaded', function() {
    Array.from(document.querySelectorAll('.copythis')).forEach(function(el) {
        const copy = document.createElement('button');
        copy.style.padding = '0';
        copy.textContent = `\u00a0[copy]`;
        const currentText = el.textContent;
        copy.addEventListener('click', function() {
			const previous = el.style.backgroundColor;
			el.style.backgroundColor = 'gold';
            navigator.clipboard.writeText(currentText);
            console.log('copied');
            this.textContent = "\u00a0[text copied]";
            setTimeout(() => { this.textContent = `\u00a0[copy]`; el.style.backgroundColor = previous; }, 500);
        })
        el.after(copy);
    })
	Array.from(document.querySelectorAll('.wikilink')).forEach(function(link) {
		link.addEventListener('mouseenter', function (ev) {
			if (globalThis.popupWindow) {
				if (globalThis.popupWindow.parentNode) globalThis.popupWindow.parentNode.removeChild(globalThis.popupWindow);
			}
			globalThis.popupWindow = popup(ev, this);
			globalThis.popupWindow.addEventListener('mouseleave', () => globalThis.popupWindow.parentNode.removeChild(globalThis.popupWindow));
		});
	})
})
function popup(ev, link) {
	const title = new URL(link.getAttribute('href'), `http://${location.hostname}`).searchParams.get('title');
	const window = document.createElement('div');
	window.style.backgroundColor = 'white';
	window.style.padding = '7px';
	window.style.border = '1px solid';
	window.style.position = 'absolute';
	window.style.top = `${ev.pageY + 5}px`;
	window.style.left = `${ev.pageX + 5}px`;
	window.style.maxWidth = '25%';
	const pagelink = document.createElement('a');
	pagelink.style.display = 'block';
	pagelink.style.fontWeight = '700';
	pagelink.style.fontSize = '1.3em';
	pagelink.textContent = title;
	pagelink.setAttribute('href', link.getAttribute('href'));
	window.appendChild(pagelink);
	const info = document.createElement('div');
	info.textContent = "Getting info...";
	window.appendChild(info);
	displayPage(info, title);
	if (!title.startsWith('Special:')) document.body.appendChild(window);
	return window;
}
function displayPage(info, title) {
	fetch(`api.php?action=parse&title=${title}&json`)
	.then((json) => json.json())
	.then(function(res) {
		info.textContent = '';
		if (!res['query'][title]['exists']) throw new Error("Page does not exist: " + title);
		if (res['query'][title].markdown.startsWith('#REDIRECT [[')) {
			info.textContent = "Redirects to...";
			const redirectTo = res['query'][title].markdown.split("\n")[0].trim().slice("#REDIRECT [[".length, -2);
			const rdrlink = document.createElement('a');
			rdrlink.style.display = 'block';
			rdrlink.style.fontWeight = '700';
			rdrlink.style.fontSize = '1.3em';
			rdrlink.href = `index.php?title=${encodeURIComponent(redirectTo)}`;
			rdrlink.textContent = redirectTo;
			info.appendChild(rdrlink);
			const moreinfo = document.createElement('div');
			moreinfo.textContent = "Getting information...";
			info.appendChild(moreinfo);
			displayPage(moreinfo, redirectTo);
			return;
		}
		const html = res['query'][title].html;
		const preview = document.createElement('span');
		preview.innerHTML = html;
		const te = preview.textContent;
		console.log(te);
		preview.textContent = preview.textContent.slice(0, 100);
		info.appendChild(preview);
		const more = document.createElement('span');
		more.textContent = te.slice(100, 400);
		more.style.display = 'none';
		info.appendChild(more);
		const morelink = document.createElement('button');
		morelink.textContent = "Show more";
		morelink.addEventListener('click', function() {
			this.style.display = 'none';
			this.previousElementSibling.style.display = 'inline';
		})
		morelink.style.display = 'block';
		if (te.slice(100, 400)) {
			console.log('showing more');
			info.appendChild(morelink);
		}
	})
	.catch(function(error) {
		const err = document.createElement('div');
		err.textContent = "ERROR: ";
		err.textContent += error;
		err.style.color = 'red';
		info.appendChild(err);
	})
}