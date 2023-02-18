  <div class="modal fade" id="custom-modal" tabindex="-1"  data-bs-backdrop="static" data-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-0">Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
  </div>

  <footer class="page-footer px-xl-4 px-sm-2 px-0 pt-3 d-flex justify-content-center align-items-center">
      <p class="text-muted">© 2022 <a href="#" title="ePLDT">ePLDT</a>, All Rights Reserved.</p>
  </footer>
  </div>

  <!-- Jquery Page Js -->
  <script src="{{ asset('assets/js/theme.js') }}"></script>
  <!-- Plugin Js -->
  <script src="{{ asset('assets/js/bundle/apexcharts.bundle.js') }}"></script>
  <script src="{{ asset('assets/js/bundle/dataTables.bundle.js') }}"></script>
  <script src="{{ asset('assets/js/bundle/daterangepicker.bundle.js') }}"></script>
  <!-- Vendor Script -->
  <script src="{{ asset('assets/js/moment.js') }}"></script>
  <script src="{{ asset('assets/js/jquery-confirm.min.js') }}"></script>
  <script src="{{ asset('assets/js/select2.min.js') }}"></script>
  <script src="{{ asset('assets/js/bs5-toast.js') }}"></script>
  
  <!-- Custom -->
  <script src="{{ asset('assets/js/custom.js') }}"></script>


  <script>
    function updateRealTime() {
      $('#realTime').html(moment(new Date).format('hh:mm:ss A'));
    }

    $(function(){
      setInterval(updateRealTime, 1000);
    });
  </script>
</body>
</html>