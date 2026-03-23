<div><b>Lista de pagos del día</b> [ <?php echo date('d/m/y', strtotime($f)); ?> ] <b>Cuenta:</b> <?php echo $cuenta_S.' -> '.$cuenta_Nom ?></div>
						<table border="1" cellpadding="3" width="100%">
							<thead>
								<tr>
									<th>Folio</th>
									<th align="right">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php $suma = 0;  foreach ($pagos as $pago): ?>
									<?php foreach ($cots as $cot) {
										if($pago['id_cot'] == $cot['id']){
											$total = $cot['total'];
											break;
										}
									} ?>
								<tr>
									<td><?php echo $pago['id_cot']; ?></td>
									<td class="text-right"><?php echo number_format($total); ?></td>
								</tr>
								<?php $suma = $suma + $total; endforeach; ?>
							</tbody>
						</table>
<h3 align="right">Total: <?php echo number_format($suma); ?></h3>
