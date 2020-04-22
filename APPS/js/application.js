console.log("application.js loaded");

var asyncTimeout = 750;

function init() {
	createApplication();
	loadFormToContainer("application.html", $("#wrapper"));
	// dynamically load page based on application status
	$.get("interfaces/submitApplication.php", function(data) {
		json = parseJSON(data, "application submission error");
		appSubmitted = Boolean(Number(json[0]));
		renderForms(appSubmitted);
	});
}

function renderForms(appSubmitted) {
	// always check status, even if submitted
	checkStatus('interfaces/transcriptsStatus.php', 'transcriptsContainer');
	checkStatus('interfaces/recommendationsStatus.php', 'recommendationsContainer');
	checkStatus('interfaces/applicationStatus.php', 'statusContainer');

	// add some navigation buttons to screens
	$(".screen").each(function (index) {
		$(this).append("<div class='next'></div>");
	})

	if (appSubmitted) {
		$(".screen:not(.status)").remove();

		// specially remove form containers in status screens
		$(".status").children(".form-container").remove();

		initSideNav();

		advanceForm("status");

		$(".screen:not(:last)").each(function (index) {
			nextButton = $('<button type="submit">Next</button>');
			nextButton.click({id: $(this).attr('id')}, function(params) {
				advanceForm($(".screen[id="+params.data.id+"]").next().attr('id'));
			})
			$(this).children(".next").append(nextButton)
		})
	}
	else {
		initSideNav();

		advanceForm("personalInfo");
		autoFill('interfaces/personalInfo.php', 'personalInfo');
		autoFillIterative('interfaces/priorDegrees.php', 'priorDegrees');
		autoFillIterative('interfaces/testScoresGRE.php', 'testScoresGRE');
		autoFillIterative('interfaces/testScoresTOEFL.php', 'testScoresTOEFL');
		autoFillIterative('interfaces/testScoresSubject.php', 'testScoresSubject');
		autoFillIterative('interfaces/workExperience.php', 'workExperience');
		autoFillStatic('interfaces/recommendations.php', 'recommendations');

		// add form delete buttons to iterative forms
		$(".template").append("<button type='button' class='removeForm'><i class='fas fa-trash'></i></button>");
		$(".removeForm").click(function() {
			if (!($(this).hasClass("template"))) {
				removeForm($(this).parent());
			}
		})

		initValidators();

		$("#personalInfo").children(".next").append('<button type="submit">Next</button>');
		validateForm($("#personalInfo"));
		
		$("#priorDegrees").children(".next").append('<button type="button" onclick="handleNext(\'interfaces/priorDegrees.php\', \'priorDegrees\', \'testScores\', true)">Next</button>');
		validateForm($("#priorDegrees1"));
		
		$("#testScores").children(".next").append('<button type="button" onclick="handleNextScores(\'testScores\', \'workExperience\')">Next</button>')
		validateForm($("#testScoresGRE1"));
		validateForm($("#testScoresTOEFL1"));
		validateForm($("#testScoresSubject1"));
		
		$("#workExperience").children(".next").append('<button type="button" onclick="handleNext(\'interfaces/workExperience.php\', \'workExperience\', \'transcripts\', true)">Next</button>')
		validateForm($("#workExperience1"));

		$("#recommendations").children(".next").append('<button type="button" onclick="advanceForm(\'status\')">Next</button>')
		$("#recommendations").find("form").each(function(index) {
			$(this).append('<button type="submit">Submit</button>');
			validateForm($(this));
		}) 

		// doesn't need validation
		$("#transcripts").children(".next").append('<button type="submit" onclick="advanceForm(\'recommendations\')">Next</button>')
	}

	$(".screen:not(:first)").each(function (index) {
		backButton = $('<button type="button">Back</button>');
		backButton.click({id: $(this).attr('id')}, function(params) {
			advanceForm($(".screen[id="+params.data.id+"]").prev().attr('id'));
		})
		$(this).children(".next").prepend(backButton)
	})

	$("form").each(function(index) {
		$(this).validate();
	});
}

function initValidators() {
	// needs validation
	$.validator.addMethod("valueNotEquals", function(value, element, arg){
		return arg !== value;
	}, "You must choose an option.");
	$.validator.addMethod("alphanumeric", function(value, element) {
		return this.optional(element) || /^[A-Za-z0-9 _.,!"'/$]+$/i.test(value);
	}, "Letters, numbers, and punctuation only.");
	$.validator.addMethod("longform", function(value, element) {
		return this.optional(element) || /^[\n\tA-Za-z0-9 _.,!"'/$]+$/i.test(value);
	}, "Letters, numbers, and punctuation only.");
	$.validator.addMethod("pastDate", function(value, element) {
	    var curDate = new Date();
	    var inputDate = new Date(value);
	    if (inputDate < curDate)
	        return true;
	    return false;
	}, "Date must be in the past.");
	$.validator.addMethod("multipleOf10", function(value, element) {
	    return !(value % 10);
	}, "Value must be a multiple of 10.");
}

function validateForm(form) {
	id = form.attr('id');
	if (id.startsWith("personalInfo")) {
		form.validate({
			rules: {
				degree: {
					valueNotEquals: "null"
				},
				term: {
					valueNotEquals: "null"
				},
				interest: {
					required: true,
					alphanumeric: true
				},
				address1: {
					required: true,
					alphanumeric: true
				},
				city: {
					required: true,
					alphanumeric: true
				},
				state: {
					valueNotEquals: "null"
				},
				zip: {
					required: true,
					digits: true,
					minlength: 5
				}
			},
			submitHandler: function(form) {
				handleNext('interfaces/personalInfo.php', 'personalInfo', 'priorDegrees', false);
				return false;
			}
		})
	}
	if (id.startsWith("priorDegrees")) {
		form.validate({
			rules: {
				degreeType: {
					valueNotEquals: "null"
				},
				gpa: {
					required: true,
					number: true,
					maxlength: 4,
					min: 0.00,
					max: 4.00
				},
				major: {
					required: true,
					alphanumeric: true
				},
				gradYear: {
					required: true,
					digits: true,
					minlength: 4,
					maxlength: 4
				},
				institution: {
					required: true,
					alphanumeric: true
				}
			}
		})
	}
	if (id.startsWith("testScoresGRE")) {
		form.validate({
			rules: {
				examDate: {
					required: true,
					date: true,
					pastDate: true
				},
				quantScore: {
					required: true,
					digits: true,
					min: 0,
					max: 170
				},
				verbalScore: {
					required: true,
					digits: true,
					min: 0,
					max: 170
				},
				writingScore: {
					required: true,
					number: true,
					maxlength: 3,
					min: 0.0,
					max: 6.0
				}
			}
		})
	}
	if (id.startsWith("testScoresTOEFL")) {
		form.validate({
			rules: {
				examDate: {
					required: true,
					date: true,
					pastDate: true
				},
				totalScore: {
					required: true,
					digits: true,
					min: 0,
					max: 120
				}
			}
		})
	}
	if (id.startsWith("testScoresSubject")) {
		form.validate({
			rules: {
				examDate: {
					required: true,
					date: true,
					pastDate: true
				},
				totalScore: {
					required: true,
					digits: true,
					min: 200,
					max: 990,
					multipleOf10: true
				},
				subject: {
					required: true,
					alphanumeric: true
				}
			}
		})
	}
	if (id.startsWith("workExperience")) {
		form.validate({
			rules: {
				startDate: {
					required: true,
					date: true,
					pastDate: true
				},
				position: {
					required: true,
					alphanumeric: true
				},
				employer: {
					required: true,
					alphanumeric: true
				}
			}
		})
	}
	if (id.startsWith("recommendations")) {
		form.validate({
			rules: {
				writerName: {
					required: true,
					alphanumeric: true
				},
				writerEmail: {
					required: true,
					email: true
				},
				writerEmployer: {
					required: true,
					alphanumeric: true
				},
				writerTitle: {
					required: true,
					alphanumeric: true
				}
			},
			submitHandler: function(form) {
				submitReference(form.id);
				return false;
			}
		});
	}
}

function initSideNav() {
	$("#menu-toggle").click(function(e) {
	  e.preventDefault();
	  $("#wrapper").toggleClass("toggled");
	});
	$(".screen").each(function (index) {
		navLink = $('<a href="#" class="list-group-item list-group-item-action side-nav-item"></a>')
		// set the text
		navLink.text($(this).children("h2").first().text());
		// set the id
		screenId = $(this).attr('id');
		navLink.attr('id', screenId+'Nav')
		// bind the link
		navLink.click({id: screenId}, function(params) {
			advanceForm(params.data.id);
		})
		$("#side-nav-list").append(navLink)
	})
	$(".side-nav-item[id='personalInfoNav']").addClass("selected");
}

function handleNext(url, id, callback, iterative) {
	if (id != null) {
		if (iterative) submitFormIterative(url, id);
		else submitForm(url, id);
	}
	advanceForm(callback);
}

function handleNextScores(id, callback) {
	// submit the scores from each input, if present
	submitFormIterative('interfaces/testScoresGRE.php', 'testScoresGRE');
	submitFormIterative('interfaces/testScoresTOEFL.php', 'testScoresTOEFL');
	submitFormIterative('interfaces/testScoresSubject.php', 'testScoresSubject');
	// regular next action
	advanceForm(callback);
}

function advanceForm(callback) {
	if (callback != null) {
		$(".screen").hide();
		$("#"+callback).show();
		$(".side-nav-item").removeClass("selected");
		$("#"+callback+"Nav").addClass("selected");
	}
}

function submitForm(url, id) {
	form = $("#"+id);
	submitFormElement(url, form);
	setTimeout(function() {autoFill(url, id);}, asyncTimeout);
}

function submitFormElement(url, form) {
	if (form.valid()) {
		serializedData = form.serialize()
		if (serializedData != "" && !form.hasClass("template")) {
			console.log("posting data", serializedData, "to", url);
			$.ajax({
				type: "POST",
				url: url,
				data: serializedData,
				success: function(response) {
					console.log(response);
				}
			});
		}
	}
	else {
		throw "Form not valid.";
	}
}

function submitReference(formId) {
	var url = 'interfaces/recommendations.php'
	submitFormElement(url, $("#"+formId));
	setTimeout(() => {  
		autoFillStatic(url, 'recommendations'); 
		checkStatus('interfaces/recommendationsStatus.php', 'recommendationsContainer');
	}, asyncTimeout);
}

function submitFormIterative(url, parentId) {
	forms = getChildForms(parentId);
	forms.each(function(index) {
		submitFormElement(url, $(this));
	})
	setTimeout(function() {autoFillIterative(url, parentId);}, asyncTimeout);
}

function createApplication() {
	$.get("interfaces/createApplication.php", function(response) {
		console.log(response);
	});
}

function autoFill(url, formId) {
	$.get(url, function(data) {
		json = parseJSON(data, "autofill error")
		form = $("#"+formId);
		fillValues(form, json);
	});
}

function autoFillIterative(url, parentId) {
	$.get(url, function(data) {
		json = parseJSON(data, "autofill error"); 
		currentForm = getChildForms(parentId).last();
		getChildForms(parentId).not(currentForm).remove();
		// TODO - create and fill in forms iteratively with the template, based on JSON return (must allow iterative return in PHP url)
		for (var i=0; i<json.length; i++) {
			if (i > 0) {
				currentForm = duplicateForm(parentId, currentForm);
			}
			fillValues(currentForm, json[i]);
		}
	});
}

function autoFillStatic(url, parentId) {
	$.get(url, function(data) {
		json = JSON.parse(data);
		getChildForms(parentId).each(function (i) {
			if (typeof json[i] === 'undefined') return false;
			fillValues($(this), json[i]);
		});
	});
}

function checkStatus(url, containerId) {
	loadFormToContainer(url, $("#"+containerId));
}

function fillValues(form, json) {
	form.removeClass("template");
	form.addClass("filled");
	for (key in json) {
		// assumes unique key name within this form
		input = form.find("input[name="+key+"], select[name="+key+"], textarea[name="+key+"]");
		if (!(json[key] == "" || json[key] == "null" || json[key] == null)) {
			input.val(json[key]);
			input.prop('disabled', true);
		}
	}
}

function duplicateLastForm(id) {
	parent = $("#"+id)
	lastForm = getChildForms(id).last();
	if (lastForm.hasClass("template")) {
		lastForm.removeClass("template");
	}
	else {
		duplicateForm(id, lastForm);
	}
}

function duplicateForm(parentId, form) {
	newForm = form.clone().prop('id', parentId.concat(parseInt(form.attr('id').split(parentId)[1])+1))
	newForm.find("label.error").remove();
	newForm.find("input, select, textarea").each(function (index) {
		$(this).prop('disabled', false);
		$(this).val(null);
	});
	validateForm(newForm);
	// insert the new form after this one
	form.after(newForm);
	return newForm;
}

function removeForm(form) {
	form.remove();
}


function getChildForms(id) {
	return $("#"+id).find("form[id^='" + id + "']");
}

function submitApplication() {
	$.post("interfaces/submitApplication.php", function(response) {
		console.log(response);
	})

	init(); 
}

function parseJSON(data, errMsg) {
	try {
		return JSON.parse(data);
	}
	catch(err) {
		console.log("ERROR:", errMsg, err)
		console.log(data);
		throw err;
	}
	return null;
}
