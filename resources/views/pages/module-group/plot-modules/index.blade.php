@extends('layouts.app')
@section('content')

<div id="myModal" class="modal">

  <div class="modal-content">
    <span class="close">&times;</span>
    <div>
        <h2>PLOT MODULE</h2>
    </div>
    <livewire:module-group.chart-form />
  </div>

</div>

@endsection

@push('scripts')
    <script>
        // Get the modal
        var modal = document.getElementById("myModal");
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        }
    </script>
@endpush