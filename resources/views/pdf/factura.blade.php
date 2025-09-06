<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Factura Nº: 01</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      margin: 40px;
    }

    .header-table {
      width: 100%;
      margin-bottom: 30px;
    }

    .info-table {
      width: 100%;
      margin-bottom: 30px;
    }

    .items-table {
      width: 100%;
      margin-top: 20px;
    }

    .items-table th, .items-table td {
      padding: 6px;
      text-align: left;
    }

    .totals-table {
      width: 100%;
      margin-top: 20px;
    }

    .payment-info {
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <!-- Cabecera con número de factura y logo -->
  <table class="header-table">
    <tr>
      <td style="width: 50%; font-size: 16px; font-weight: bold;">FACTURA Nº: 01</td>
      <td style="width: 50%; text-align: right;">
        <img src="logo.png" alt="Logo" style="width: 120px;">
      </td>
    </tr>
  </table>

  <!-- Datos del cliente y empresa -->
  <table class="info-table" width="100%" cellspacing="0" cellpadding="10" style="border-collapse: collapse;">
    <tr>
      <td style="width: 50%; vertical-align: top; border-right: 2px solid #b462e2; padding-right: 20px;">
        <strong>Datos del Cliente</strong><br>
        Nombre: Alba Castro<br>
        Email: hola@unsitiogenial.es<br>
        Teléfono: 911-234-5678<br>
        Dirección: Calle Cualquiera 123, Cualquier Lugar
      </td>
      <td style="width: 50%; vertical-align: top; padding-left: 20px;">
        <strong>Datos de la Empresa</strong><br>
        Nombre: Alba Castro<br>
        Email: hola@unsitiogenial.es<br>
        Teléfono: 911-234-5678<br>
        Dirección: Calle Cualquiera 123, Cualquier Lugar
      </td>
    </tr>
  </table>
  

  <!-- Detalle de productos/servicios -->
  <table class="items-table" width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">
    <thead>
      <tr>
        <th>Detalle</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
        <tr style="border-top: 3px solid #b462e2; height: 30px;">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
      <tr><td>Diseño de Logotipo</td><td>1</td><td>10 €</td><td>10 €</td></tr>
    <tfoot>
      <tr>
        <td colspan="4" style="height: 5px;"></td>
      </tr>
      <tr>
        <td colspan="4" style="border-top: 3px solid #b462e2;"></td>
      </tr>

      <tr>
        <td></td>
        <td>IVA</td>
        <td>21%</td>
        <td>200 €</td>
      </tr>

      <tr>
        <td colspan="4" style="height: 15px;"></td>
      </tr>

      <tr>
        <td style=""></td>
        <td colspan="2" style="border: 2px solid #b462e2; text-align: center; font-weight: bold;">Total</td>
        <td style="border: 2px solid #b462e2; font-weight: bold;">200 €</td>
      </tr>
      
      
    </tfoot>
  </table>

  <!-- Información de pago -->
  <div style="width:40%; border: 2px solid #b462e2; border-radius: 12px; padding: 15px; width: fit-content; background-color: #f8e6fc; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-top: 30px;">
    <strong>Información de Pago</strong><br>
    Transferencia bancaria<br>
    Banco Bonello<br>
    Número de cuenta: 0000 0000 0000 0000
  </div>

  <!-- Mensaje de agradecimiento -->
  <div style="text-align: center; margin-top: 50px; font-size: 24px; font-weight: bold; color: #581177;">
    ¡Gracias por preferirnos!
  </div>
  
</body>
</html>
