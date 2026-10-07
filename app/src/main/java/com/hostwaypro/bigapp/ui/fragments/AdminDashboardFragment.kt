package com.hostwaypro.bigapp.ui.fragments

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.navigation.fragment.findNavController
import com.hostwaypro.bigapp.R
import com.hostwaypro.bigapp.databinding.FragmentAdminDashboardBinding
import com.hostwaypro.bigapp.ui.viewmodel.AuthViewModel
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class AdminDashboardFragment : Fragment() {

    private var _binding: FragmentAdminDashboardBinding? = null
    private val binding get() = _binding!!
    private val authViewModel: AuthViewModel by viewModels()

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentAdminDashboardBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        binding.btnLogout.setOnClickListener {
            authViewModel.signOut()
        }

        binding.cardWorkers.setOnClickListener {
            findNavController().navigate(R.id.action_adminDashboardFragment_to_workersFragment)
        }

        binding.cardWorkRegistration.setOnClickListener {
            findNavController().navigate(R.id.action_adminDashboardFragment_to_bulkWorkEntryFragment)
        }

        binding.cardPending.setOnClickListener {
            findNavController().navigate(R.id.action_adminDashboardFragment_to_pendingWorkersFragment)
        }

        binding.cardReports.setOnClickListener {
            findNavController().navigate(R.id.action_adminDashboardFragment_to_reportsFragment)
        }

        binding.cardSettings.setOnClickListener {
            findNavController().navigate(R.id.action_adminDashboardFragment_to_settingsFragment)
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
