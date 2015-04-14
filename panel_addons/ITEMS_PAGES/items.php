<?php 

include 'inc/config.php';

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

/*$database->query('SELECT itemlog.*, items.*, COUNT(itemlog.`index`) AS found FROM itemlog 
  INNER JOIN items 
  ON itemlog.`index` = items.`index` 
  GROUP BY itemlog.`method`, itemlog.`quality` 
  ORDER BY `found` DESC');
$log = $database->resultset();*/

?>
    <?php include("inc/nav.php"); ?>
    <div class="stats-body">
      <div style="padding:10px">
        <table id="log" class="table table-bordered table-striped table-condensed" style="cursor:pointer">
          <thead>
            <tr>
              <th>Date</th>
              <th>Index</th>
              <th>Name</th>
              <th style="text-align:center">Item</th>
              <th style="text-align:center">Method</th>
              <th style="text-align:center">Quality</th>
              <th style="text-align:center">Color</th>
              <th style="text-align:center">Class</th>
              <th style="text-align:center">Slot</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
<?php include "inc/footer.php"; ?>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  </div>
  <script>
  $(document).ready(function() {

    var items = $('#log').DataTable( {
      "processing": false,
      "serverSide": true,
      "ajax": {
        "url": "inc/server_processing.php",
        "type": "POST",
        "data": {
          "type": "allitems"
        }
      },
      "pagingType": "full",
      //"dom": "<lf<t>pi>",
      "columns": [
        { "data": "time",
          "searchable": false,
          "render": function ( data, type, full, meta ) {
            return '<h5 style="display: block;margin-left: auto;margin-right: auto;">'+data+'</h5>';
          }
        },
        { "data": "index", "visible" : false, "searchable": false },
        { "data": "name",
          "render": function ( data, type, full, meta ) {
            return '<h5 style="display: block;margin-left: auto;margin-right: auto;">'+data+'</h5>';
          }
        },
        { "data": "image",
          "searchable": false,
          "render": function ( data, type, full, meta ) {
            return '<img style="display: block;margin-left: auto;margin-right: auto;" width=75px height=75px src="'+data+'">';
          }
        },
        { "data": "method_text",
          "render": function ( data, type, full, meta ) {
            return '<h5 style="text-align:center;display: block;margin-left: auto;margin-right: auto;">'+data+'</h5>';
          }
         },
        { "data": "quality_text",
          "render": function ( data, type, full, meta ) {
            return '<h5 style="text-align:center;display: block;margin-left: auto;margin-right: auto;">'+data+'</h5>';
          }
         },
        { "data": "quality_color", "visible" : false, "searchable": false },
        { "data": "class",
          "render": function ( data, type, full, meta ) {
            return '<h5 style="text-align:center;display: block;margin-left: auto;margin-right: auto;">'+data+'</h5>';
          }
         },
        { "data": "slot",
          "render": function ( data, type, full, meta ) {
            return '<h5 style="text-align:center;display: block;margin-left: auto;margin-right: auto;">'+data+'</h5>';
          }
         },
         { "data": "type", "visible" : false, "searchable": true },
      ],
      "order": [[0, 'desc']],
      "createdRow": function ( row, data, index ) {
        $('td', row).eq(2).css('background-color',data.quality_color);
      }
    });
    $('#log tbody').on('click', 'tr', function () {
      $('#modal').modal('show');
      $.ajax({
        type: "GET",
        url: "inc/getitems.php",
        data: 'id=' + items.cell(this, 1).data(),
        success: function(msg){
          $('#modal').html(msg);
        }
      });
    });
    $('#log tbody').on('click', 'tr', function () {
      items.search(items.cell(this, 2).data()).draw();
      $('.dataTables_filter input').val(items.cell(this, 2).data());
    });
  });
  </script>
</body>
</html>