<div class="modal custom-modal" tabindex="-1" role="dialog" id="add_car" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
                <form id="addCarForm" action="#" method="POST">
                    @csrf
                <div class="modal-steps-form step-active">
                    <div class="step-main-repeater ">
                        <div class="step-form-title text-center">

                            <img src="{{asset('public/assets/images/car.svg')}}"/>
                            <h4>Add the vehicle in need of service</h4>
                        </div>
                        <div class="custom-radio ">
                            <select name="year" id="yearSelect" >
                                <option>Year</option>
                            </select>
                        </div>
                        <div class="custom-radio ">
                            <select name="make" id="selectMake">
                                <option>Make</option>
                            </select>
                        </div>
                        <div class="custom-radio ">
                            <select name="model" id="modalSelect">
                                <option>Model</option>
                            </select>
                        </div>
                        <div class="custom-radio ">
                            <select name="color">
                                <option>Color</option>
                                <option>Black</option>
                                <option>Blue</option><option>Brown</option><!-- comment -->
                                <option>Green</option><!-- comment -->
                                <option>Grey</option><!-- comment --><option>Purple</option><!-- comment -->
                                <option>Red</option>
                                <option>Silver</option><!-- comment -->
                                <option>White</option><!-- comment -->
                                <option>Yellow</option><!-- comment --><option>hazel</option><!-- comment -->

                            </select>
                        </div>
                        <div class="custom-radio">
                            <button type="submit" class="btn btn-next "  >Add Car</button>
                        </div>
                        </form>
                    </div>
                </div>


            </div>
          </div>
        </div>
      </div>

