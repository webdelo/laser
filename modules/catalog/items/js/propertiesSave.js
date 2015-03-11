$(function(){
	var inputsPropertiesMonitoring = function () {
		var editPropertiesValue = new inputs;
		editPropertiesValue.setSettings({'element' : '.editPropertyValue', 'showError': false})
			.setCallback(function (response) {
				var type = typeof response;
				if ( typeof response !== 'number' ) {
					alert('Error '+response);
				}
			})
			.init();

		(new inputs)
			.setSettings({'element' : '.newProperty', 'showError': false})
			.setCallback(function (response) {
				if ( typeof response === 'number' ) {
					reloadPropertiesBlock();
				}
			})
			.init();

		(new inputs)
			.setSettings({'element' : '.newPropertyPart', 'showError': false})
			.setCallback(function (response) {
				if ( typeof response === 'number' ) {
					reloadPropertiesBlock();
				}
			})
			.init();

		(new inputs)
			.setSettings({'element' : '.editPropertyPart', 'showError': false})
			.setCallback(function (response) {
				if ( typeof response === 'number' ) {
					reloadPropertiesBlock();
				}
			})
			.init();
		
		var falls = new selectsFalls;
		falls.setStep('.measurementsCategories', function(select$, options) {
				var mesaures$ = select$.parents('.property').find('.measurements');
				var value$ = select$.parents('.property').find('.editRelation');
				
				this.addOptions(options, mesaures$);
				
				var val = value$.val();
				if ( value$.val() !== '' )
					mesaures$.children(':first').change();
				
			});
			
		
		var saveRelation = new form;
		saveRelation.setSettings({'form':'.saveRelation'})
					.setLoader(new loaderLight)
					.setCallback(function(response){
						if ( typeof response === "number" && saveRelation.getObject().find('.editRelation').val() !== '' ) {
							saveRelation.getObject().find('input[name=id]').val(response);
						} else {
							saveRelation.getObject().find('input[name=id]').val('')
						}
					})
					.init();
		
		
		var submitForm = function(){
			var submit$ = $(this).parents('.saveRelation').find('.saveRelationSubmit');
			submit$.click();
		};
		
		$('.measurements').on('change', function() {
			if ( $(this).parents('.property').find('.editRelation').val() !== '' ) {
				submitForm.call(this);
				$(this).parents('.property').find('.viewMode .measure').text($(this).children(':selected').text());
			}
		});
		$('.editRelation').on('blur', function() {
			submitForm.call(this);
			$(this).parents('.property').find('.viewMode .value').text($(this).val());
			$(this).parents('.property')
				   .find('.viewMode .measure')
				   .text(
						$(this).parents('.property')
							   .find('.measurements')
							   .children(':selected')
							   .text()
					);
			if ( $(this).val() === '' ) {
				$(this).parents('.property').find('.viewMode .measure').text('указать значение');
			}
		});
	}
	inputsPropertiesMonitoring();
	
	var reloadPropertiesBlock = function(){
		$('.propertiesBlocks').htmlFromServer({
			'callback':inputsPropertiesMonitoring
		});
	};
});