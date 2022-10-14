"use strict";

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance"); }

function _iterableToArray(iter) { if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

(function ($) {

  function _showJobs(cat, active) {
    //check category variable
    if (active == "yes") {

      //show all jobs
      let jobs = $( ".OpenPositions-job_listing" ).slideDown();

    } else {
      //remove all jobs
      let jobs = $( ".OpenPositions-job_listing" ).slideUp();

      //show only selected jobs
      jobs.each(function (){
        if($(this).data( "cat") == cat) {
          $(this).slideDown();
        }
      })
    }
  }


  OpenPositions =
  /*#__PURE__*/
  function () {
    function OpenPositions(settings) {
      _classCallCheck(this, OpenPositions);

      this.element = settings.element;
      this.rss_url = settings.rss_url;
      this.init();
    }

    _createClass(OpenPositions, [{
      key: "init",
      value: function init() {
        this.category = "";
        this.getJobsData(this.rss_url);
        this.dropdownSet = false;
      }
    }, {
      key: "getJobsData",
      value: function getJobsData(url) {
        var _this = this;

        fetch(url).then(function (response) {
          return response.json();
        }).then(function (data) {

          _this.data = data;

          _this.buildHtml(_this.data);

          _this.handleFilterClick();
        });
      }
    }, {
      key: "buildHtml",
      value: function buildHtml(data) {
        var _this2 = this;
        var htmlArr = [];
        var catSet = new Set();
        let departmentName = '';
          catSet.add(`<p class="OpenPositions-category_item active" data-cat="All Positions">All Positions`);
          data['jobs'].forEach(function (el) {

          //set number per category using departmentNames object we created earlier
          departmentName = el.departments[0].name.split(" - ");
          departmentName = departmentName[0];

          //add departments/categories
          catSet.add(`<p class="OpenPositions-category_item" data-cat="${el.departments[0].name}">${departmentName}`);

          //create html for each job
          var html = `<a href='../open-positions-details?gh_jid=${el.id}' class='OpenPositions-job_listing animated fadeInUp' data-cat="${el.departments[0].name}">
                     <div class='OpenPositions-listing_content_container'>
                        <div class='OpenPositions-listing_content'>
                           <p class='OpenPositions-listing_title'>${el.title}</p>
                           <p class='OpenPositions-listing_location'>${el.location.name} </p>
                        </div></div>
                     <div class='OpenPositions_listing-apply_container'><div class='OpenPositions-job_apply'>
                      <div href="#" class="OpenPositions_button_circle">
                      <div class="OpenPositions_link_arrow"><i class="fas fa-arrow-right"></i></div>
                      </div>
                     </div></div> </a>`;
          htmlArr.push(html);
        });

        _this2.element.querySelector(".OpenPositions-main_container").innerHTML = htmlArr.join("");

        if (!_this2.dropdownSet) {
            _this2.element.querySelector(".OpenPositions-category_container").innerHTML = _toConsumableArray(catSet).join("");
        }

        _this2.dropdownSet = true;

      }
    }, {
      key: "handleFilterClick",
      value: function handleFilterClick() {
        var _this3 = this;

        var catDropdown = this.element.querySelectorAll(".OpenPositions-category_item");


        catDropdown.forEach(function (el)  {
          //add click function to each category
          el.addEventListener("click", function () {
            //"this" at this point is selected category
            let that = this;
            let active = "";

            catDropdown.forEach(function (ele)  {
              let eleAttr = ele.getAttribute('data-cat');
              console.log(eleAttr);
                if (ele == that && eleAttr == 'All Positions'){
                    if (ele.classList.contains('active')) {
                        return;
                    }
                    active = 'yes';
                    _showJobs(el.dataset.cat, active);
                    ele.classList.add("active");
                    ele.removeEventListener("click", ele, false);
                }
              //check selected category and remove active from rest
              else if (ele == that && eleAttr != 'All Positions') {

                //check if selected cat is active - used to toggle all jobs
               /* if (ele.classList.contains('active')) {
                  active = 'yes';
                }*/
                    if (ele.classList.contains('active')) {
                        return;
                    }
                // show / hide jobs
                _showJobs(el.dataset.cat, active);
                    ele.classList.add("active");
                    ele.removeEventListener("click", ele, false);
                    //check again selected cat
               /* if (ele.classList.contains('active')) {
                  ele.classList.remove("active");
                } else {
                  ele.classList.add("active");
                }
*/

              } else { //remove active class for non clicked categories
                ele.classList.remove("active");
              }
            });

          });
        })

      } //end of handleFilterClick
    }
    ]);

    return OpenPositions;
  }();
})(jQuery);
