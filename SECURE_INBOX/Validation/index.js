console.log("this")

let result = {
    "role": false
}


submitBtn.addEventListener("click", async (e) => {
    e.preventDefault()
    console.log("Clicked!")

    let key = "ema_live_84lELLSXMhYTOqnhaSRykiGo4Y3SLQcssnIN34up"
    let email = document.getElementById("username").value
    let url = `https://api.emailvalidation.io/v1/info?apikey=${key}&email=${email}`
    let res = await fetch(url)
    let result = await res.json()


    let str = ``
    for (key of Object.keys(result)) {
        str = str + `<div>${key}: ${result[key]}</div>`
    }
    console.log(str)
    resultCont.innerHTML = str
})


