
$(document).ready(function () {

    /*
     *   
     * Biding Function
     *
     */
    
    $('.btnBidWithIncrease').click(function(){
        if ($('#user_id').val() == '') {
            location.href = siteUrl + '/Users/login';
        } else {

            var auctionBidAmount = parseFloat($(this).attr('data-max-bid')) + parseFloat($('.auctionBid').val());
            
            var user_id = $('#user_id').val();
            var car_id = $(this).attr('data-id');
           
            $.ajax({
                url: siteUrl + '/Users/addUpdateUserBid',
                type: 'post', // performing a POST request
                data: {
                    biding_amount: auctionBidAmount,
                    user_id: user_id,
                    vehicle_id: car_id,
                },
                success: function (result)
                {
                    alertify.notify('Your bid is successfully save.', 'success');
                    
                    setTimeout(function(){
                        location.reload();
                    }, 2500)
                   
                }
            });
        }
    });

    $('.btnBid').click(function () {

        if ($('#user_id').val() == '') {
            location.href = siteUrl + '/Users/login';
        } else {

             var auctionBidAmount =  $('#custom_auction_bid').val()
             
             if( auctionBidAmount < $(this).attr('data-max-bid') ){
                 alert('Bid should be higher than maximum bid value.');
                 return false;
             }
            
            
            
            var user_id = $('#user_id').val();
            var car_id = $(this).attr('data-id');
           
            $.ajax({
                url: siteUrl + '/Users/addUpdateUserBid',
                type: 'post', // performing a POST request
                data: {
                    biding_amount: auctionBidAmount,
                    user_id: user_id,
                    vehicle_id: car_id,
                },
                success: function (result)
                {
                    alertify.notify('Your bid is successfully save.', 'success');
                    
                    setTimeout(function(){
                        location.reload();
                    }, 2500)
                    
                    
                    /*$.ajax({
                        url: siteUrl + "/Vehicles/getRecentOffers",
                        method: 'get',
                        data: { vehicle_id: $('#vehicle_id').val()},
                        success: function (result) {
                            $("#recent_offers").html(result);
                        }
                    });
                    
                    if (result == 'timeupdated') {
                        location.reload();
                    } else {
                        alertify.notify('Your bid is successfully save.', 'success');
                    }*/

                }
            });
        }

    });

    /* $('.btnQuickSearch').click(function () {

        var vehicle_regions = $('#vehicle_regions').val();
        var vehicleMake = $('#vehicleMake').val();
        var vehicleModel = $('#vehicleModel').val();
        var minyear = $('#min_year').val();
        var maxyear = $('#max_year').val();
        if (minyear > maxyear) {
            alertify.alert('Alert', 'Max Year must be grater then Min Year.');
            return false;
        } else {
            $.ajax({
                url: siteUrl + '/Users/getquicksearchData',
                type: 'post', // performing a POST request
                data: {
                    vehicle_regions: vehicle_regions,
                    vehicleMake: vehicleMake,
                    vehicleModel: vehicleModel,
                    minyear: minyear,
                    maxyear: maxyear,
                },
                success: function (result)
                {
                    $('#auctionData').html(result);
                    $('[data-countdown]').each(function () {
                        var $this = $(this);
                        $this.countdown(Date.parse($(this).data('countdown')), function (event) {
                            if (event.type == 'finish') {
                                $.ajax({
                                    url: siteUrl + '/Users/sellCarToUser',
                                    data: {vehicle_id: $(this).attr('data-vehicleid')},
                                    type: 'POST',
                                    success: function (res) {
                                        if (res == 'emailsuccess') {
                                            $this.parent().parent().parent().remove();
                                            alertify.notify('Request has been send successfully.', 'success');
                                            return false;
                                        } else if (res == 'success') {
                                            alertify.notify('This Vehicle have been removed from list.', 'success');
                                            return false;
                                        } else if (res == 'fail') {
                                            $this.parent().parent().parent().remove();
                                            alertify.notify('We are unable to send your request.', 'success');
                                            return false;
                                        } else {
                                            return false;
                                        }
                                    },
                                    error: function (errormessage) {
                                        console.log(errormessage);
                                    },
                                });
                            } else {
                                $this.html(event.strftime('<span>%H:%M:%S</span>'));
                            }
                        });
                    });

                }
            });
        }

    }); */

    $('.addtoFav').click(function () {
        var user_id = $('#user_id').val();
        var vehicle_id = $('#vehicle_id').val();
        if ($('#is_favourite').val() == '') {
            var is_favourite = '1';
        } else {
            var is_favourite = $('#is_favourite').val();
        }
        $.ajax({
            url: siteUrl + '/Vehicles/addtofavourite',
            type: 'post', // performing a POST request
            data: {
                user_id: user_id,
                vehicle_id: vehicle_id,
                is_favourite: is_favourite
            },
            success: function (result)
            {
                if (result == 'success' || result == 'fav') {
                    $('#is_favourite').val('1');
                    $('.addtoFav i').removeClass($('.addtoFav i').attr('class')).addClass('fa fa-heart');
                    alertify.notify('This Vehicle have been added to favourite.', 'success');
                } else {
                    $('#is_favourite').val('0');
                    $('.addtoFav i').removeClass($('.addtoFav i').attr('class')).addClass('fa fa-heart-o');
                    alertify.notify('This Vehicle have been removed to favourite.', 'success');
                }
            }
        });
    });

//    $('.sortbyopt').change(function () {
//        var sortopt = $(this).val();
//        $.ajax({
//            url: siteUrl + '/Users/getsortAuctionData',
//            type: 'post', // performing a POST request
//            data: {
//                sortopt: sortopt,
//            },
//            success: function (data)
//            {
//                $('#auctionData').html(data);
//                $('[data-countdown]').each(function () {
//                    var $this = $(this);
//                    $this.countdown(Date.parse($(this).data('countdown')), function (event) {
//                        if (event.type == 'finish') {
//                            $.ajax({
//                                url: siteUrl + '/Users/sellCarToUser',
//                                data: {vehicle_id: $(this).attr('data-vehicleid')},
//                                type: 'POST',
//                                success: function (res) {
//                                    if (res == 'emailsuccess') {
//                                        $this.parent().parent().parent().remove();
//                                        alertify.notify('Request has been send successfully.', 'success');
//                                        return false;
//                                    } else if (res == 'success') {
//                                        alertify.notify('This Vehicle have been removed from list.', 'success');
//                                        return false;
//                                    } else if (res == 'fail') {
//                                        $this.parent().parent().parent().remove();
//                                        alertify.notify('We are unable to send your request.', 'success');
//                                        return false;
//                                    } else {
//                                        return false;
//                                    }
//                                },
//                                error: function (errormessage) {
//                                    console.log(errormessage);
//                                },
//                            });
//                        } else {
//                            $this.html(event.strftime('<span>%H:%M:%S</span>'));
//                        }
//                    });
//                });
//            }
//        });
//    });

//    $('#checkbox1').click(function () {
//        var sortopt = $(this).val();
//        $.ajax({
//            url: siteUrl + '/Users/getmyAuctionData',
//            type: 'post', // performing a POST request
//            data: {
//                sortopt: sortopt,
//            },
//            success: function (data)
//            {
//                $('#auctionData').html(data);
//                $('[data-countdown]').each(function () {
//                    var $this = $(this);
//                    $this.countdown(Date.parse($(this).data('countdown')), function (event) {
//                        if (event.type == 'finish') {
//                            $.ajax({
//                                url: siteUrl + '/Users/sellCarToUser',
//                                data: {vehicle_id: $(this).attr('data-vehicleid')},
//                                type: 'POST',
//                                success: function (res) {
//                                    if (res == 'emailsuccess') {
//                                        $this.parent().parent().parent().remove();
//                                        alertify.notify('Request has been send successfully.', 'success');
//                                        return false;
//                                    } else if (res == 'success') {
//                                        alertify.notify('This Vehicle have been removed from list.', 'success');
//                                        return false;
//                                    } else if (res == 'fail') {
//                                        $this.parent().parent().parent().remove();
//                                        alertify.notify('We are unable to send your request.', 'success');
//                                        return false;
//                                    } else {
//                                        return false;
//                                    }
//                                },
//                                error: function (errormessage) {
//                                    console.log(errormessage);
//                                },
//                            });
//                        } else {
//                            $this.html(event.strftime('<span>%H:%M:%S</span>'));
//                        }
//                    });
//                });
//            }
//        });
//    });

    $("#vehicleMake").select2({ placeholder: lbl_select_make, allowClear: true });
    $("#vehicleModel").select2({ placeholder: lbl_select_model, allowClear: true });

    $("#vehicleMake").select2({
        minimumInputLength: 1,
        minimumResultsForSearch: 10,
        allowClear: true,
        placeholder: lbl_select_make,
        ajax: {
            url: siteUrl + '/vehicles/getAllMakes',
            dataType: "json",
            type: "GET",
            data: function (params) {

                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.text
                        }
                    })
                };
            }
        }
    });

    

    $("#vehicleModel").select2({
        minimumInputLength: 1,
        minimumResultsForSearch: 10,
        allowClear: true,
        placeholder: lbl_select_model,
        ajax: {
            url: siteUrl + '/vehicles/getModelByMake',
            dataType: "json",
            type: "GET",
            data: function (params) {

                var queryParameters = {
                    term: params.term,
                    make_id: $('#vehicleMake').val()
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.text
                        }
                    })
                };
            }
        }
    });

    $('.btnQuickSearch').click(function(){
        getAucionData($(this).val(), $('#checkbox1:checked').val(), '', $('#chkFavourites:checked').val(), $('#vehicleMake').val(),  $('#vehicleModel').val(), $('#vehicle_regions').val(), $('#min_year').val(), $('#max_year').val()     );
    });

    $('.sortbyopt').change(function () {
        getAucionData($(this).val(), $('#checkbox1:checked').val(), '', $('#chkFavourites:checked').val(), $('#vehicleMake').val(),  $('#vehicleModel').val(), $('#vehicle_regions').val(), $('#min_year').val(), $('#max_year').val());
    });

    $('#checkbox1').click(function () {
        getAucionData($('.sortbyopt').val(), $('#checkbox1:checked').val(), '', $('#chkFavourites:checked').val(), $('#vehicleMake').val(),  $('#vehicleModel').val(), $('#vehicle_regions').val(), $('#min_year').val(), $('#max_year').val());
    });

    $('#chkFavourites').click(function () {
        getAucionData($('.sortbyopt').val(), $('#checkbox1:checked').val(), '', $('#chkFavourites:checked').val(), $('#vehicleMake').val(),  $('#vehicleModel').val(), $('#vehicle_regions').val(), $('#min_year').val(), $('#max_year').val());
    });
});

function getAucionData(sortOn, isShortList, vehicleId, isFavourite, makeName, modelName, regionCode, minYear, maxYear ) {
    $.blockUI({ message: '<h4>Please wait...</h4>' });
    $.ajax({
        url: siteUrl + '/Users/getAuctionData',
        type: 'POST', // performing a POST request
        data: {
            sortOn: sortOn,
            shortListed: isShortList,
            vehicleId: vehicleId,
            isFavourite: isFavourite,
            makeName: makeName,
            modelName: modelName, 
            regionCode: regionCode,
            minYear: minYear,
            maxYear: maxYear,
        },
        success: function (data) {
            $.unblockUI();
            $('html, body').animate({
                    scrollTop: $(".vehicle-listing").offset().top - 200
                }, 1000);
            
            $('#auctionData').html(data);
            $('[data-countdown]').each(function () {
                var $this = $(this);
                $this.countdown(Date.parse($this.data('countdown')), function (event) {

                    if (event.type == 'finish') {
                        $.ajax({
                            url: siteUrl + '/Users/sellCarToUser',
                            data: {vehicle_id: $(this).attr('data-vehicleid')},
                            type: 'POST',
                            success: function (res) {
                                if (res == 'emailsuccess') {
                                    $this.parent().parent().parent().remove();
                                    alertify.notify('Request has been send successfully.', 'success');
                                    return false;
                                } else if (res == 'success') {
                                    alertify.notify('This Vehicle have been removed from list.', 'success');
                                    return false;
                                } else if (res == 'fail') {
                                    $this.parent().parent().parent().remove();
                                    alertify.notify('We are unable to send your request.', 'success');
                                    return false;
                                } else {
                                    return false;
                                }
                            },
                            error: function (errormessage) {
                                console.log(errormessage);
                            },
                        });
                    } else {
                        $this.html(event.strftime('<span>%I:%M:%S</span>'));
                    }
                });
            });
        },
        error: function (errormessage) {
            console.log(errormessage);
        },
    });
}

