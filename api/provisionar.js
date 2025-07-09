module.exports = async function (context, req) {
    const id = req.query.id || (req.body && req.body.id);

    context.res = {
        status: 200,
        body: `Provisionando localidade com ID: ${id}`
    };
}




add provisionar function
