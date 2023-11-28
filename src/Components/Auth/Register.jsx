import { Box, Button, Typography } from "@mui/material";
import { Link } from "react-router-dom";

const Register = () => {
  return (
    <Box
      sx={{
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        width: "100%",
        minHeight: "90vh",
      }}
    >
      <Box
        className="login-form"
        sx={{
          width: { xs: "70%", md: "40%" },
          minHeight: "400px",
          padding: "10px",
        }}
      >
        <Typography
          textAlign="center"
          color="#fff"
          sx={{
            fontSize: { xs: "20px", md: "30px" },
            padding: "10px",
            textAlign: "center",
          }}
        >
          Create An Account
        </Typography>
        <Box
          sx={{
            display: "flex",
            alignItems: "center",
            flexDirection: "row !important",
            gap: "10px",
            marginBottom: "0 !important",
            transform: "translateY(10px)",
          }}
        >
          <div>
            <label htmlFor="firstname">First Name</label>
            <input type="text" id="firstname" />
          </div>
          <div>
            <label htmlFor="lastname">Last Name</label>
            <input type="text" id="lastname" />
          </div>
        </Box>
        <div>
          <label htmlFor="email">Email</label>
          <input type="text" id="email" />
        </div>
        <div>
          <label htmlFor="password">Password</label>
          <input type="password" id="password" />
        </div>
        <div>
          <label htmlFor="confirm-password">Confirm Password</label>
          <input type="password" id="confirm-password" />
        </div>
        <Box sx={{ width: "100%", display: "flex", justifyContent: "center" }}>
          <Button
            variant="contained"
            color="secondary"
            sx={{ margin: "10px 30%" }}
          >
            Register
          </Button>
        </Box>
        <Box>
          <Typography
            sx={{
              fontSize: { xs: "12px", md: "15px" },
              color: "#fff",
              textAlign: "center",
              marginTop: "10px",
            }}
          >
            Don't have an account?
            <Link to="/login"> Sign in</Link>
          </Typography>
        </Box>
      </Box>
    </Box>
  );
};

export default Register;
