export class Ajax{
    
    //Devuelve un objeto de tipo ajax.
    constructor() {
        this.request = new XMLHttpRequest();
    }

    open(method,url){
        this.request.open(method,url);
    }

    onreload(data){
        this.request.onload(()=>{
            //Acá va el cuerpo de todo lo que vamos a usar.

        });
    }

    
    send(){
        this.request.send(data);
    }
}

export default{
    Ajax
}