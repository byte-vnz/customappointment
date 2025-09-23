@extends('layout')



@section('content')
<style>
    #tbl-yes-no th {
        text-align: center;
    }
    #tbl-yes-no td:not(:first-child) {
        text-align: center;
    }
</style>
<div class="alert d-none mt-4" id="alert-box-form" role="alert"></div>
<form name="health-mainfrm" id="health-mainfrm" method="post" class="needs-validation" novalidate>
    <div class="row mt-4">
        <div class="col">
            
            <div class="card">
                <div class="card-header">
                    <h1> PARAÑAQUE CITY - Health Declaration Form </h1>
                </div>
                <div class="card-body">
                  
                        <p class="card-text" style="font-size:18px;">
                            Alinsunod sa RA 11469, o Bayanihan to Heal as One Act, at guidelines ng COVID-19, na ipinatutupad sa Lungsod ng Parañaque, at bilang pag-iingat at pag-iwas sa paglaganap ng COVID-19 sa Lungsod ng Parañaque, kinakailangan mong sagutan ang health declaration form na ito. Sa pagsagot nito, pinapahintulutan mo 
                        ang pag processo ng personal/ sensitibong personal/ maselang impormasyong iyong ibinigay.
                        </p>

                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="refno"> Appointment Reference Number</label>
                                    <input type="text" class="form-control" id="refno" name="refno" >
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        
        
        </div>
    </div>

    <div class="row mt-4 mb-4">
        <div class="col">
            
            <div class="card">
                <div class="card-header">
                    <h5> PERSONAL INFORMATION </h5>
                </div>
                <div class="card-body">
                  

                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lastname"> APELYIDO *</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="firstname"> PANGALAN *</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middlename"> GITNANG PANGALAN </label>
                                    <input type="text" class="form-control" id="middlename" name="middlename" >
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                        </div>

                    
                        
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country"> KABANSAAN *</label>
                                    <input type="text" class="form-control" id="country" name="country" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dateinp"> PETSA NGAYON *</label>
                                    <input type="date" class="form-control" id="dateinp" name="dateinp" value="{{ date('Y-m-d') }}" disabled>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">KASARIAN *</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="M">MALE</option>
                                        <option value="F">FEMALE</option>   
                                    </select>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div> 
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="age"> EDAD *</label>
                                    <input type="number" class="form-control" id="age" name="age" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cno"> MATATAWAGANG NUMERO *</label>
                                    <input type="text" class="form-control" id="cno" name="cno" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="work"> TRABAHO *</label>
                                    <input type="text" class="form-control" id="work" name="work" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="addrwork"> ADDRESS NG OPISINA *</label>
                                    <input type="text" class="form-control" id="addrwork" name="addrwork" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="addrhome"> ADDRESS NG TAHANAN *</label>
                                    <input type="text" class="form-control" id="addrhome" name="addrhome" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email"> EMAIL ADDRESS *</label>
                                    <input type="text" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tempread"> TEMPERATURE READING *</label>
                                    <input type="number" step="any" class="form-control" id="tempread" name="tempread" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>                        
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reason"> SADYA NG PAGPUNTA *</label>
                                    <input type="text" class="form-control" id="reason" name="reason" required>
                                    <div class="invalid-feedback">This is a required field.</div>
                                </div>  
                            </div>                                
                        </div>


                    </div>
                </div>
   
        
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5> 1. Bumiyahe ka ba palabas ng bansa sa nakaraan 14 na araw? </h5>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input linked-text" data-allow-txt="true" type="radio" name="q1" id="q1" value="1" required>
                        <label class="form-check-label" for="q1">
                            OO
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input linked-text" data-allow-txt="false" type="radio" name="q1" id="q1_2" value="0" required>
                        <label class="form-check-label" for="q1_2">
                            HINDI
                        </label>
                        <div class="invalid-feedback">This is a required field.</div>
                    </div>

                    <div class="form-row form-group mt-4">
                        <label for="q1ans"> KUNG OO SAAN?</label>
                        <input type="text" class="form-control" id="q1ans" name="q1ans" disabled>
                    </div>
                </div>

            </div>
        </div>
    </div>

   <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5> 2. Bumiyahe ka ba sa ibang lugar sa Metro Manila simula ng ECQ noong March 17, 2020? </h5>
                </div>
                <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input linked-text" data-allow-txt="true" type="radio" name="q2" id="q2" value="1" required>
                            <label class="form-check-label" for="q2">
                               OO
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input linked-text" data-allow-txt="false" type="radio" name="q2" id="q2_2" value="0" required>
                            <label class="form-check-label" for="q2_2">
                                HINDI
                            </label>
                            <div class="invalid-feedback">This is a required field.</div>
                        </div>

                        <div class="form-row form-group mt-4">
                            <label for="q2ans"> KUNG OO SAAN?</label>
                            <input type="text" class="form-control" id="q2ans" name="q2ans" disabled>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5> 3. May nakasalamuha ka ba na may lagnat/ ubo/ sipon o namamagang lalamunan sa nakaraang 14 na araw?  </h5>
                </div>
                <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="q3" id="q3" value="1" required>
                            <label class="form-check-label" for="q3">
                               MERON
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="q3" id="q3_2" value="0" required>
                            <label class="form-check-label" for="q3_2">
                                WALA
                            </label>
                            <div class="invalid-feedback">This is a required field.</div>
                        </div>

                        <div class="form-row form-group mt-4">
                            <label for="q3ans"> OTHER</label>
                            <input type="text" class="form-control" id="q3ans" name="q3ans" >
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5> 4. May nakasama ka ba sa trabaho o sa bahay na nakumpirmang may COVID-19?  </h5>
                </div>
                <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="q4" id="q4" value="1" required>
                            <label class="form-check-label" for="q4">
                               MERON
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="q4" id="q4_2" value="0" required>
                            <label class="form-check-label" for="q4_2">
                                WALA
                            </label>
                            <div class="invalid-feedback">This is a required field.</div>
                        </div>

                        <div class="form-row form-group mt-4">
                            <label for="q4ans"> OTHER</label>
                            <input type="text" class="form-control" id="q4ans" name="q4ans" >
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5> 5. Nakakaramdam ka ba ng alinman sa mga sumusunod?  </h5>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table" id="tbl-yes-no">
                                    <thead>
                                        <tr>
                                            <th width="50%"></th>
                                            <th width="25%">OO</th>
                                            <th width="25%">HINDI</th>
                                        </tr>
                                    </thead>
                                        <tr>
                                            <td>LAGNAT</td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_1" id="q5_1" value="1" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_1" id="q5_1" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="500">UBO</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_2" id="q5_2" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_2" id="q5_2" value="0" required>
                                                </div>                                           

                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="500">SIPON</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_3" id="q5_3" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_3" id="q5_3" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr>                                     

                                        <tr>
                                            <td width="500">MADALING MAPAGOD</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_4" id="q5_4" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_4" id="q5_4" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr> 

                                        <tr>
                                            <td width="500">PANANAKIT NG KATAWAN</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_5" id="q5_5" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_5" id="q5_5" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr>                                                             

 
                                        <tr>
                                            <td width="500">HIRAP SA PAGHINGA</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_6" id="q5_6" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_6" id="q5_6" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="500">MADALAS NA PAGDUMI</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_7" id="q5_7" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_7" id="q5_7" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="500">PAMAMAGA NG LALAMUNAN</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_8" id="q5_8" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_8" id="q5_8" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr> 
                                        
                                        <tr>
                                            <td width="500">PANANAKIT NG ULO</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_9" id="q5_9" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_9" id="q5_9" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td width="500">WALANG PANSALA O PANG AMOY</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_10" id="q5_10" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_10" id="q5_10" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td width="500">PANINIKIP NG DIBDIB</td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_11" id="q5_11" value="1" required>
                                                </div>
                                            </td>
                                            <td width="200">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input position-static" type="radio" name="q5_11" id="q5_11" value="0" required>
                                                </div>                                           
                                            </td>
                                        </tr>                              
                                </table>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5> DECLARATION </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p style="font-size:18px;">
                            Ang impormasyong aking ibinigay ay totoo, tama at kumpleto. Nauunawaan ko na ang hindi ko pagsagot sa anumang katanungan o pagbibigay ng hindi totoong kasagutan at may karampatang parusa sa ilalim ng batas.<br>
                            Ako ay kusa at malayang nagbibigay pahintulot sa paglikom at pagbabahagi ng mga personal na impormasyong aking ibinigay alinsunod sa BPLO Paranaque Covid-19 Health Declaration Form.<br>
                            Pinapaunawa na ang impormasyong ibinigay sa itaas ay gagamitin lang ayon sa BPLO Paranaque Health Declaration Form ayon sa Data Privacy Act of 2012.<br>
                            </p>
                        </div>
                    </div>
 
                </div>
             

            </div>
        </div>
    </div>
    <div class="row mt-3 mb-5">
        <div class="col-md-6">
            <button type="submit" id="health-submit-btn" class="btn btn-primary">
                Submit
                <span class="spinner-border spinner-border-sm ml-3 d-none" id="submit-spiner" role="status" aria-hidden="true"></span>
                <span class="sr-only">Loading...</span>
            </button>
        </div>
    </div>

</form>  
@endsection