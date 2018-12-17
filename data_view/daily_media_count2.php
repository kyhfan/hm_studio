<?php
// 설정파일
include_once "../include/autoload.php";

$mnv_f = new mnv_function();
$my_db         = $mnv_f->Connect_MySQL();
/*
	if (isset($_SESSION['ss_mb_id']) == false)
	{
		//header('Location: index.php');
		echo "<script>location.href='index.php'</script>";
		exit;
	}
*/
include "./head.php";

?>

<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">캠페인 좋아요 참여자 수 PC / Mobile</h1>
			</div>
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
					<!-- 필리핀 -->
					<div id="daily_applicant_count_div1" style="display:block">
						<table class="table table-hover">
							<thead>
								<tr><th>날짜</th><th>유입매체</th><th>PC</th><th>Mobile</th><th>Unique</th><th>Total</th></tr>
							</thead>
							<tbody>
								<?php
								$pc_total_all = 0;
								$mobile_total_all = 0;
								$unique_total = 0;
								$all_total = 0;
								$daily_date_query	= "SELECT mb_like_regdate FROM member_info_like WHERE 1 Group by substr(mb_like_regdate,1,10) ORDER BY mb_like_regdate DESC";
								$date_res			= mysqli_query($my_db, $daily_date_query);
								while($date_daily_data = mysqli_fetch_array($date_res))
								{
									$daily_date		= substr($date_daily_data['mb_like_regdate'],0,10);
									$media_query	= "SELECT mb_like_media, COUNT( mb_like_media ) media_cnt FROM member_info_like WHERE 1 AND mb_like_regdate LIKE  '%".$daily_date."%' GROUP BY mb_like_media";
									$media_res		= mysqli_query($my_db, $media_query);

									$unique_query	= "SELECT * FROM member_info_like WHERE 1 AND mb_like_regdate LIKE  '%".$daily_date."%' GROUP BY mb_like_ipaddr";
									$unique_count	= mysqli_num_rows(mysqli_query($my_db, $unique_query));

									unset($media_name);
									unset($media_cnt);
									unset($pc_cnt);
									unset($mobile_cnt);
									unset($unique_cnt);
									$total_media_cnt = 0;
									$total_mobile_cnt = 0;
									$total_pc_cnt = 0;
									while ($media_daily_data = mysqli_fetch_array($media_res))
									{
										$media_name[]	= $media_daily_data['mb_like_media'];
										$media_cnt[]	= $media_daily_data['media_cnt'];
										$pc_query		= "SELECT * FROM member_info_like WHERE 1 AND mb_like_regdate LIKE  '%".$daily_date."%' AND mb_like_media='".$media_daily_data['mb_like_media']."' AND mb_like_gubun='PC'";
										$pc_count		= mysqli_num_rows(mysqli_query($my_db, $pc_query));
										$mobile_query	= "SELECT * FROM member_info_like WHERE 1 AND mb_like_regdate LIKE  '%".$daily_date."%' AND mb_like_media='".$media_daily_data['mb_like_media']."' AND mb_like_gubun='MOBILE'";
										$mobile_count	= mysqli_num_rows(mysqli_query($my_db, $mobile_query));
										// $unique_query	= "SELECT * FROM ".$_gl['design_info_table']." WHERE 1 AND design_regdate LIKE  '%".$daily_date."%' AND design_media='".$media_daily_data['design_media']."' GROUP BY design_ipaddr";
										// $unique_count	= mysqli_num_rows(mysqli_query($my_db, $unique_query));
										$pc_cnt[]		= $pc_count;
										$mobile_cnt[]	= $mobile_count;
										// $unique_cnt[]	= $unique_count;

									}
									$rowspan_cnt =  count($media_name);
									$i = 0;
									foreach($media_name as $key => $val)
									{
								?>
								<tr>
									<?
										if ($i == 0)
										{
									?>
									<td rowspan="<?=$rowspan_cnt?>">
										<?php echo $daily_date?>
										<!-- <a id="excelDown" href="excel_download_member.php?date=<?=$daily_date?>">
<span>엑셀 다운로드</span>
</a> -->
									</td>
									<?
										}
									?>
									<td><?=$val?></td>
									<td><?=number_format($pc_cnt[$i])?></td>
									<td><?=number_format($mobile_cnt[$i])?></td>
									<td>-</td>
									<td><?=number_format($media_cnt[$i])?></td>
								</tr>
								<?php
										$total_media_cnt += $media_cnt[$i];
										$total_mobile_cnt += $mobile_cnt[$i];
										$total_pc_cnt += $pc_cnt[$i];
										$i++;
									}
									$pc_total_all += $total_pc_cnt;
									$mobile_total_all += $total_mobile_cnt;
									$unique_total += $unique_count;
									$all_total += $total_pc_cnt+$total_mobile_cnt;
								?>
								<tr bgcolor="skyblue">
									<td colspan="2">합계</td>
									<td><?php echo number_format($total_pc_cnt)?></td>
									<td><?php echo number_format($total_mobile_cnt)?></td>
									<td><?php echo number_format($unique_count)?></td>
									<td><?php echo number_format($total_media_cnt)?></td>
								</tr>

								<?
								}
								?>
							</tbody>
							<div class="total-wrap" style="float: right; background: lightgrey; padding: 20px; margin-bottom: 10px;">
								<?
								echo "<span style='display:inline-block; margin-right:10px;'>PC: ".$pc_total_all."</span>";
								echo "<span style='display:inline-block; margin-right:10px;'>MOBILE: ".$mobile_total_all."</span>";
								echo "<span style='display:inline-block; margin-right:10px;'>UNIQUE: ".$unique_total."</span>";
								echo "<span style='display:inline-block;'>TOTAL: ".$all_total."</span>";
								?>
							</div>
						</table>
					</div>
				</div>
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</div>
	<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
</body>

</html>
