<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <table>
    <tr>
      @foreach($fields as $field)
      <th>{{$field}}</th>
      @endforeach
    </tr>
    @foreach($data as $row)
    <tr>
        @foreach($row as $value)
        <td>
            {{$value}}
        </td>
        @endforeach
    </tr>
    @endforeach
    </table>
  </body>
</html>