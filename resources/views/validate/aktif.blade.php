<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TOKEN VALIDATE</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        
        <script nonce="defd92b5-1ea5-4de2-af48-aa1133338d80">(function(w,d){!function(a,e,t,r){a.zarazData=a.zarazData||{};a.zarazData.executed=[];a.zaraz={deferred:[]};a.zaraz.q=[];a.zaraz._f=function(e){return function(){var t=Array.prototype.slice.call(arguments);a.zaraz.q.push({m:e,a:t})}};for(const e of["track","set","ecommerce","debug"])a.zaraz[e]=a.zaraz._f(e);a.zaraz.init=()=>{var t=e.getElementsByTagName(r)[0],z=e.createElement(r),n=e.getElementsByTagName("title")[0];n&&(a.zarazData.t=e.getElementsByTagName("title")[0].text);a.zarazData.x=Math.random();a.zarazData.w=a.screen.width;a.zarazData.h=a.screen.height;a.zarazData.j=a.innerHeight;a.zarazData.e=a.innerWidth;a.zarazData.l=a.location.href;a.zarazData.r=e.referrer;a.zarazData.k=a.screen.colorDepth;a.zarazData.n=e.characterSet;a.zarazData.o=(new Date).getTimezoneOffset();a.zarazData.q=[];for(;a.zaraz.q.length;){const e=a.zaraz.q.shift();a.zarazData.q.push(e)}z.defer=!0;for(const e of[localStorage,sessionStorage])Object.keys(e||{}).filter((a=>a.startsWith("_zaraz_"))).forEach((t=>{try{a.zarazData["z_"+t.slice(7)]=JSON.parse(e.getItem(t))}catch{a.zarazData["z_"+t.slice(7)]=e.getItem(t)}}));z.referrerPolicy="origin";z.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(a.zarazData)));t.parentNode.insertBefore(z,t)};["complete","interactive"].includes(e.readyState)?zaraz.init():a.addEventListener("DOMContentLoaded",zaraz.init)}(w,d,0,"script");})(window,document);</script>
    </head>
    <body class="hold-transition lockscreen">
        <div class="lockscreen-wrapper">
            <h3 class="text-center"><b>token</b> Validate</h3>
            <div class="container-fluid">
                <div class="card">    
                    @if($row->status === 'Disetujui')
                    <div class="card-body">                    
                        <div class="table">
                            <table class="table table-bordered ">
                                <tr>
                                    <td>KODE SURAT</td>
                                    <td>{{$row->cuti->no_surat}}</td>
                                </tr>
                                <tr>
                                    <td>KEPERLUAN</td>
                                    <td>PENGAJUAN CUTI</td>
                                </tr>
                                <tr>
                                    <td>DISETUJUI OLEH</td>
                                    <td>{{$row->cuti->wrektor->name}}</td>
                                </tr>
                                <tr>
                                    <td>NPM</td>
                                    <td>{{$row->mahasiswa->username}}</td>
                                </tr>
                                <tr>
                                    <td>NAMA</td>
                                    <td>{{$row->mahasiswa->name}}</td>
                                </tr>
                                <tr>
                                    <td>PROGRAM STUDI</td>
                                    <td>{{$row->mahasiswa->mahasiswa->prodi->nama_prodi}}</td>
                                </tr>
                                <tr>
                                    <td>KODE AKADEMIK</td>
                                    <td>{{$row->akademik_id}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>                                         
                    @else           
                        <div class="card-body">                    
                        Maaf, Token tidak valid, berkas yang kamu ajukan tidak terdaftar atau belum disetujui oleh pihak Operator Akademik.
                        </div>                                  
                    @endif
                </div>            
            </div> 
        </div>           
    </body>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>
