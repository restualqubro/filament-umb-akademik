<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>App | <?=$title?></title>
  <!-- Tell the browser to be responsive to screen width -->

<style>
body {
    /* position:fixed; */
    margin-left : 50px;
    margin-right: 50px;
}

.logo-top {
  
  padding-left:-80px;    
  width : 100px;
}

.box {
  float :right;
  font-size:10pt;
  background-color:white;
  max-height:25px;  
  padding : 0 5px 0 5px;
}

p {
  font-size:10pt;
}
p, .left {
  font-size:10pt;
  padding-bottom : 0;
  margin-bottom : 0;
}

p, ol {
  margin-top : 0;
  margin-bottom: 0;
  font-size:10pt;
}

.tempat {
  padding-left:25px;
}

table {
  padding-left:20px;
  font-size:10pt;
}
table, .table-in {
  padding-left: -4px;
}

.kanan {
  min-width:400px;
  float:right;
}


</style>  
</head>
<body>
   <img src="data:image/png;base64,{{ base64_encode(file_get_contents( "images/logo-umb.png" )) }}" class="logo-top"> 
   {{-- <img src="{{ asset('/images/logo-umb.png')}}" class="logo-top"> --}}
  {{-- <img src="{{ asset('/storage/sites/01J47B3N6G2ZNMD3083P4RFNHX.png')}}" class="logo-top"> --}}
  <?php 
//   $a = $row->row_array();
  ?>
  <div class="box">
  Barito Kuala, <br/>
  </div>
  <br/><br/>
  <table class="table-paragraph">
    <tr>
      <td>Nomor</td>
      <td>:</td>
      {{-- <td></td> --}}
    </tr>
    <tr>
      <td>Lampiran</td>
      <td>:</td>
      <td></td>
    </tr>
    <tr>
      <td>Perihal</td>
      <td>:</td>
      <td>Persetujuan Izin Cuti Akadmeik</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>
        <br/><br/><p class="left">
          Kepada Yth :<br/>
          Saudara/i <b></b><br/>
          di-<br/><span class="tempat">tempat</span><br/><br/>
          Assalamu'alaikum Warahmatullahi Wabarakatuh<br/><br/>
          Ba'da salam teriring doa semoga yang kita rencanakan dan kerjakan selalu bernilai ibadah disisi Allah Subhanahu Wa Ta'ala.<br/><br/>
          Menindaklanjuti surat pengajuan saudara perihal Persetujuan Izin Cuti Akademik, maka dengan ini kami dari pihak
          Universitas Muhammadiyah Banjarmasin mengizinkan cuti akademik (kuliah) pada semester yang diajukan dengan keterangan
          mahasiswa sebagai berikut : <br/>

          <!-- <b class="judul">Nama<br/>NPM<br/>Program Studi<br/>Alasan<br/></b>
          <b class="titik">:<br/>:<br/>:<br/>:</b> -->
      </p>
          <b>
          <table class="table-in">
            <tr>
              <td><b>Nama</b></td>
              <td><b>:</b></td>
              <td><b></b></td>
            </tr>
            <tr>
              <td>NPM</td>
              <td>:</td>
              <td></td>
            </tr>
            <tr>
              <td>Program Studi</td>
              <td>:</td>
              <td></td>
            </tr>
            <tr>
              <td>Alasan</td>
              <td>:</td>
              <td></td>
            </tr>
          </table></b>
          <p>
            Selama menjalani cuti akademik, yang bersangkutan tetap berkewajiban untuk :
            <br/>1. Membayar Spp selama cuti sebesar 25%
            <br/>2. Melapor ke bagian Administrasi Akademik Rektorat LT. 1 Kampus Utama untuk membicarakan <br/><span style="padding-left : 12px;">kelanjutan studinya.</span> 
            <br/><br/>
            Demikian surat persetujuan ini kami sampaikan untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.<br/><br/>
            Wassalamu'alaikum Warahmatullahi Wabarakatuh.
          </p>
      </td>
    </tr>
  </table>
  <div class="kanan">        
    Wakil Rektor 1<br/><br/><br/>
      <img src="data:image/png;base64, {!! $image !!}">
    <br/>
    <b><u></u></b><br/>
    <b>NIK. </b>
</div>
<div class="kiri">
  <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
  <b><u>Tembusan : </u></b>
  <table>
    <tr>
      <td>1. </td>
      <td>Administrasi Keuangan</td>
    </tr>
    <tr>
      <td>2. </td>
      <td></td>
    </tr>
    <tr>
      <td>3. </td>
      <td>Arsip</td>
    </tr>
  </table>
</div>
          
  <!-- 
  </div><br/><br/><br/>
  <p class="left">Kepada Yth :<br/>
  <ol>
  <li>Rektor</li> 
  <li>Dekan Fakultas Keperawatan dan Ilmu Kesehatan</li> 
  <li>Wakil Dekan Bidang Akademik</li> 
  <li>Ketua Program Studi Profesi Ners</li> 
  </ol>
  Universitas Muhammadiyah Banjarmasin<br/>
  Di<br/>
  <span class="tempat">Tempat</span><br/><br/>
  Assalamu’alaikum Warahmatullahi Wabarakaatuh<br/>
  Dengan Hormat Saya yang bertanda tangan di bawah ini :<br/>
  <table>
    <tr>
      <td>Nama Lengkap</td>
      <td>:</td>
      <td></td>
    </tr>
    <tr>
      <td>NIM</td>
      <td>:</td>
      <td></td>
    </tr>    
    <tr>
      <td>Program Studi</td>
      <td>:</td>
      <td></td>
    </tr>
    <tr>
      <td>Kode Akademik</td>
      <td>:</td>
      <td></td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td>:</td>
      <td></td>
    </tr>
    <tr>
      <td>No Telp/HP</td>
      <td>:</td>
      <td></td>
    </tr>
  </table>
  <br/>
Bersama ini saya ijin mengajukan surat 
<b>Permohonan Cuti</b>
di Universitas Muhammadiyah Banjarmasin. Sebagai pertimbangan, bersama ini saya lampirkan:
<ol>
  <li>Surat keterangan bebas tanggungan perpustakaan</li> 
  <li>Surat keterangan lunas administrasi keuangan</li> 
  </ol>
<br/>     
Demikian surat permohonan ini diajukan untuk ditindak lanjuti sebagai mana mestinya. Atas perhatiannya diucapkan terimakasih.<br/><br/> 
Wassalamu’alaikum Warahmatullahi Wabarakaatuh<br/>

<div class="kanan">
    Banjarmasin, <br/><br/>
    Disetujui Oleh,<br/>
    Wakil Rektor 3<br/><br/><br/>
    
    <br/>
    ()<br/>
</div><br/><br/> -->
</p>
</body>
</html>
