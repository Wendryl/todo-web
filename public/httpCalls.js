export async function loadActivities() {
  const result = await fetch("http://localhost:8080/api/todoItems")
    .then((response) => response.json())
    .then((data) => data)
    .catch((error) => {
      throw error;
    });

  return result;
}

export async function createActivity(newActivity) {
  const result = await fetch("http://localhost:8080/api/todoItems", {
    method: "POST",
    body: JSON.stringify(newActivity),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => data)
    .catch((error) => {
      throw error;
    });

  return result;
}

export async function updateActivity(id, newActivity) {
  return fetch(`http://localhost:8080/api/todoItems/${id}`, {
    method: "PUT",
    body: JSON.stringify(newActivity),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((data) => data)
    .catch((error) => {
      throw error;
    });
}

export async function deleteActivity(id) {
  return fetch(`http://localhost:8080/api/todoItems/${id}`, {
    method: "DELETE",
  })
    .then((data) => data)
    .catch((error) => {
      throw error;
    });
}

export async function authenticate(login, password) {
  const result = await fetch("http://localhost:8080/api/auth/login", {
    method: "POST",
    body: JSON.stringify({
      login,
      password,
    }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((data) => data)
    .catch((error) => {
      throw error;
    });

  return result;
}

export async function logout() {
  const result = await fetch("http://localhost:8080/api/auth/logout", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((data) => data)
    .catch((error) => {
      throw error;
    });

  return result;
}




