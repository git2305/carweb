
//var l = window.location;
//var BASE_URL = l.protocol+"//"+l.host+"/"+l.pathname.split('/')[1]+"/admin/";

$(document).ready(function(){
	$('.searchAdmin').click(function(){
	
		var searchBoxVal = $('.searchBox').val();
		var searchField = $('.searchField').val();
		if(searchBoxVal != 'all' && searchField == ''){
			alert('Please enter text for search');
		}else{
			$.ajax({
				url: BASE_URL+'Users/usersearch',
				type: 'post', // performing a POST request
				data: {
					searchBoxVal: searchBoxVal,
					searchField: searchField,
				},
				success: function (result)
				{
					$('.searchField').val('');
					$('.userTableData').html(result);
					
				}
			});
		}
	});
	
	$('.searchVehicle').click(function(){
	
		var searchBoxVal = $('.searchBox').val();
		var searchField = $('.searchField').val();
		if(searchBoxVal != 'all' && searchField == ''){
			alert('Please enter text for search');
		}else{
			$.ajax({
				url: BASE_URL+'Vehicles/vehiclesearch',
				type: 'post', // performing a POST request
				data: {
					searchBoxVal: searchBoxVal,
					searchField: searchField,
				},
				success: function (result)
				{
					$('.searchField').val('');
					$('.vehicleTableData').html(result);
					
				}
			});
		}
	});
	
	$('.searchsoldVehicle').click(function(){
	
		var searchBoxVal = $('.searchBox').val();
		var searchField = $('.searchField').val();
		if(searchBoxVal != 'all' && searchField == ''){
			alert('Please enter text for search');
		}else{
			$.ajax({
				url: BASE_URL+'Vehicles/soldvehiclesearch',
				type: 'post', // performing a POST request
				data: {
					searchBoxVal: searchBoxVal,
					searchField: searchField,
				},
				success: function (result)
				{
					$('.searchField').val('');
					$('.vehicleTableData').html(result);
					
				}
			});
		}
	});
	
	$('.searchpurVehicle').click(function(){
	
		var searchBoxVal = $('.searchBox').val();
		var searchField = $('.searchField').val();
		if(searchBoxVal != 'all' && searchField == ''){
			alert('Please enter text for search');
		}else{
			$.ajax({
				url: BASE_URL+'Vehicles/purchasedvehiclesearch',
				type: 'post', // performing a POST request
				data: {
					searchBoxVal: searchBoxVal,
					searchField: searchField,
				},
				success: function (result)
				{
					$('.searchField').val('');
					$('.vehicleTableData').html(result);
					
				}
			});
		}
	});
	
	
	$('.searchauctionVehicle').click(function(){
	
		var searchBoxVal = $('.searchBox').val();
		var searchField = $('.searchField').val();
		if(searchBoxVal != 'all' && searchField == ''){
			alert('Please enter text for search');
		}else{
			$.ajax({
				url: BASE_URL+'Vehicles/auctionvehiclesearch',
				type: 'post', // performing a POST request
				data: {
					searchBoxVal: searchBoxVal,
					searchField: searchField,
				},
				success: function (result)
				{
					$('.searchField').val('');
					$('.vehicleTableData').html(result);
					
				}
			});
		}
	});
	
	$('.searchCategory').click(function(){
	
		var searchBoxVal = $('.searchBox').val();
		var searchField = $('.searchField').val();
		if(searchBoxVal != 'all' && searchField == ''){
			alert('Please enter text for search');
		}else{
			$.ajax({
				url: BASE_URL+'Vehicles/vehiclecategorysearch',
				type: 'post', // performing a POST request
				data: {
					searchBoxVal: searchBoxVal,
					searchField: searchField,
				},
				success: function (result)
				{
					$('.searchField').val('');
					$('.vehicleCatTableData').html(result);
					
				}
			});
		}
	});
	
	$('.searchCompany').click(function(){
	
		var searchBoxVal = $('.searchBox').val();
		var searchField = $('.searchField').val();
		if(searchBoxVal != 'all' && searchField == ''){
			alert('Please enter text for search');
		}else{
			$.ajax({
				url: BASE_URL+'Companies/searchcompanies',
				type: 'post', // performing a POST request
				data: {
					searchBoxVal: searchBoxVal,
					searchField: searchField,
				},
				success: function (result)
				{
					$('.searchField').val('');
					$('.companyTableData').html(result);
					
				}
			});
		}
	});
	
	$('.changeLanguage').change(function(){
	
		var language = $(this).val();
		
		$.ajax({
			url: BASE_URL+'CmsPages/allPagesbylang',
			type: 'post', // performing a POST request
			data: {
				language: language,
			},
			success: function (result)
			{
				$('.dataByLang').html(result);
				
			}
		});
	});
	
	$('.unpaidinv').click(function(){
	
		alert('test');
		var vehicle_id = $(this).attr('data-id');
		
		$.ajax({
			url: BASE_URL+'Vehicles/vehiclepaid',
			type: 'post', // performing a POST request
			data: {
				vehicle_id: vehicle_id,
			},
			success: function (result)
			{
				if(result == 'paid'){
					$('.unpaidinv').html('Paid');
					$('.unpaidinv').css('background','green');
					$('.unpaidinv').css('padding','3px 12px');
				}else{
					return false;
				}
				
			}
		});
	});
});

