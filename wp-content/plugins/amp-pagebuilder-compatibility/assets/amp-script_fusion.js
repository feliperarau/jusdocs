var tabs = document.getElementsByClassName('tab-link');
//document.getElementsByClassName('tab-link');
for (var i = tabs.length - 1; i >= 0; i--) {
	tabs[i].addEventListener('click',tab_click);
}

function tab_click(event) {
	var self =event.target;
	var nxt_selector = self.parentNode.parentNode.querySelectorAll("li");
	//document.querySelectorAll(".parent > .child1");
	if(self.hasAttribute('data-href')){
		var target_tab = event.target.getAttribute('data-href');
	}
	else{
		var target_tab = self.parentNode.getAttribute('data-href');
	}

var main_class = document.getElementsByClassName('fu-tabs');	
var activity = document.querySelectorAll('.active');
for (var i = activity.length - 1; i >= 0; i--) {	
	activity[i].classList.remove('active');
	activity[i].classList.remove('in');
}
//console.log(main_class[0].childNodes.length);
var activity = main_class[0].querySelector(target_tab);
activity.classList.add('active');
activity.classList.add('in');
	if(self.hasAttribute('data-href')){
		self.parentNode.classList.add('active');
	}else{
		self.parentNode.parentNode.classList.add('active');
	}

}