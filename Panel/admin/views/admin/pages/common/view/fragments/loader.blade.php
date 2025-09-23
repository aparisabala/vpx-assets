<style type="text/css">
  
.theGlobalLoader {
    
    z-index: 499999;
    position: fixed;
    display: none;
    width: 100%;
    height: 100%; 
    background-color:rgba(255,255,255,.08);
    top: 0;
    margin-top: 0;
}


.activeGlobalLoader {


    width: 100%;
    height: 100%; 
}

.theGlobalLoaderImg {

    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%; 
}
#inflate {

    position: fixed;
    bottom: 50px;
    z-index: 500000;
    right: 10px;
    width: 300px;
    min-height: 0;
    background: transparent;
}

.errorBase {

    position: fixed;
    z-index: 1560;
    width: 100%;
    height: 100%;
    margin-top: 0;
    top: 0;
    left: 0;
    overflow-y: scroll;
    background-color: rgba(22,160,133,.95);
    transition: all .3s;
    display: none;
}

#errorHeader {

    width: 100%;
    position: relative;
    padding: 0 8px;
    display: flex;
    justify-content: flex-end;
    align-items: flex-end;
    color: black;
}

.showErros{

    position: relative;
    padding: 20px 0;
    width: 500px;
    color: white;
    font-size: 16px;
    margin: auto;

}

.activateErrors {

    margin-top: 0px !important;
}


.theDownloadLoader {

    z-index: 499;
    position: fixed;
    width: 100%;
    height: 100%; 
    background-color:rgba(0,0,0,.9);
    top: 0;
    margin-top: 0;
    display: none;
}
.theDownloadLoaderContent {

    z-index: 500;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%; 

}
</style>
<div id="inflate" style="position: fixed;bottom: 50px;z-index: 500000;right: 10px;width: 300px;min-height: 0;background: transparent;">
</div>
<div  id="theGlobalLoader" class="theGlobalLoader">
    <div class="theGlobalLoaderImg">
      <div class="unselectable">
        <img src="{{ url('/statics/images/system/loader6.gif') }}" style="height: 100px;width: 100px">
      </div>
    </div>
</div>
<div  id="errorBase" class="errorBase">
	<div id="errorHeader">
		<i class="fa fa-times" style="padding: 16px;cursor: pointer;" aria-hidden="true" id="closeError"></i>
	</div>
	<div id="showErros" class="showErros">
	</div>
</div>
<div class="theDownloadLoader" id="theDownloadLoader">
	<div>
		<i class="fa fa-times" style="padding: 16px;cursor: pointer;" aria-hidden="true" id="closeDownload"></i>
	</div>
    <div class="theDownloadLoaderContent">
      <div class="unselectable">
        
        <div style="color: white;font-size: 22px;">
        	<img src="{{ url('/statics/images/system/loader6.gif') }}" style="height: 40px;width: 40px">
        	Creating Document, Do not close this window...
        </div>
      </div>
    </div>
</div>