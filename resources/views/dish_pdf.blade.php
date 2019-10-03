<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
      @if($dish)
        <h3>{{$dish->name}}</h3>
      @endif
    <table>
        <tr>
        @foreach($fields as $field)
            <th>{{$field}}</th>
        @endforeach
        </tr>
        @foreach($data as $row)
        <tr>
            @foreach($fields as $field)
            <td>
                {{$row[$field]}}
            </td>
            @endforeach
        </tr>
        @endforeach
        <tr>
            <td colspan="5" style="text-align:right"> <b>{{$total}}</b></td>
        <tr>
    </table>
  </body>
</html>