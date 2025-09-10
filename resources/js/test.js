import axios from "axios";

function myFunction(route) {
    console.log("working");
    console.log(route);
}

window.myFunction = myFunction;
window.submitForm = submitForm;
window.axios = axios;

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

axios
    .get("http://localhost/ams/public/api/test/2")
    .then((response) => {
        let posts = response.data;
        let list = document.getElementById("posts-list");
        list.innerHTML = "";

        posts.slice(0, 5).forEach((post) => {
            let li = document.createElement("li");
            li.innerHTML = `<strong>${post.title}</strong>: ${post.body}`;
            list.appendChild(li);
        });
    })
    .catch((error) => console.error("Error:", error));

function submitForm(request) {
    let postData = new FormData();
    postData.append("_token", request.get("_token"));
    postData.append("name", "Don Dominick");

    axios
        .post(request.get("uri"), postData, {
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                "Content-Type": "application/json",
            },
        })
        .then((response) => {
            console.log("Form has been submitted");
            console.log(response);
            console.log(response.data);
        })
        .catch((error) => {
            console.log(error);
            console.log("NOT WORKING");
        });
}
