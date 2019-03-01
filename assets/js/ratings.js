$(document).ready(function () {

  // collecting the ids from the products on the page
  var productIds = [];
  $(".buyButton").each(function () {
    productIds.push($(this).val());
  })

  // checks if there are any products on the page
  if (productIds.length > 0) {
    getRatingInfo(productIds);
    setEventListener();
  }

});

function getRatingInfo(productIds) {
  $.post("index.php", {
      // POST Data
      page: "ajax",
      action: "getRatingInfo",
      productIds: productIds
    },
    function (data) {
      console.log(data);
      
      
      
      // card where the product is displayed
      $(".card").each(function () {

        // get the productId that is displayed on the card
        let productId = $(this).find(".buyButton").val();

        
        data.forEach(ratingInfo => {
          console.log(ratingInfo);
          if (ratingInfo.product_id == productId) {
            let avg = parseFloat(ratingInfo.avgRating).toFixed(2)
            $(this).find(".avgRating").text(avg);
            if (ratingInfo.userRating) {
              $(this).find(".yourRating").text(ratingInfo.userRating);
            }
          
            let card = $(this);
            colorStars(card, ratingInfo.userRating);
          }
        });
      });
    });
      
};

function setEventListener() {
  $(".card").each(function () {

    let productId = $(this).find(".buyButton").val();

    $(this).find("#stars li").click(function () {

      // getting value from clicked star
      foundRating = $(this).data("value");

      $(this).addClass('selected');
      // Send to the server
      $.post("index.php", {
          // POST Data
          page: "ajax",
          action: "updateRating",
          productId: productId,
          rating: foundRating
        },
        function (data) {
          var productIds = [];
          $(".buyButton").each(function () {
            productIds.push($(this).val());
          })
          getRatingInfo(productIds);
        });
    });
  });
}

function colorStars(cardBody, $userRating) {
  cardBody.find("#stars li").each(function () {
    if ($(this).attr('data-value') <= $userRating) {
      $(this).addClass('selected');
    } else {
      $(this).removeClass('selected');
    }
  });
}