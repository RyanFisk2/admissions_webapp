var reviewPage = 0;
displayPage(reviewPage);

function displayPage(n) {
	//display pages in order
	var formPages = document.getElementsByClassName("reviewForm");

	formPages[n].style.display="block";
	//set next and back buttons
	
	if(n == 0){
		//no back button on first page
		document.getElementById("prev").style.display="none";
	}else if (n == (formPages.length - 1)){
		//no next button on the last page
		document.getElementById("next").style.display="none";
	}
}

function handleButton(n) {
	//display the next or previous page based on the currently displayed page
	var formPages = document.getElementsByClassName("reviewForm");

	formPages[reviewPage].style.display = "none";
	//increment the current page by n (n will always be +- 1)
	
	reviewPage += n;

	displayPage(reviewPage);
}

